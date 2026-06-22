<?php
session_start();
error_reporting(0);
include('includes/config.php');

// ══════════════════════════════════════════════════════════
//  🔑  GEMINI API KEY — Load API keys from gitignored file
//      Get one free at: https://aistudio.google.com/apikey
// ══════════════════════════════════════════════════════════
require_once('includes/api_keys.php');
define('GEMINI_MODEL',   'gemini-2.0-flash');

// Initialize chat history
if (!isset($_SESSION['chatHistory'])) {
    $_SESSION['chatHistory'] = [];
}

// Clear chat
if (isset($_POST['clear_chat'])) {
    $_SESSION['chatHistory'] = [];
    header('Location: carbot.php');
    exit;
}

// ─── Fetch live car inventory from DB for Gemini context ──────────────
function getCarInventoryContext($dbh) {
    try {
        $sql = "SELECT
                    tblbrands.brandname,
                    tblvehicles.vehiclestitle,
                    tblvehicles.priceperday,
                    tblvehicles.fueltype,
                    tblvehicles.modelyear,
                    tblvehicles.seatingcapacity
                FROM tblvehicles
                JOIN tblbrands ON tblbrands.id = tblvehicles.vehiclesbrand
                ORDER BY tblvehicles.priceperday ASC";
        $rows = $dbh->query($sql)->fetchAll(PDO::FETCH_OBJ);
        if (empty($rows)) return "No cars currently available.";

        $lines = [];
        foreach ($rows as $car) {
            $lines[] = "- {$car->brandname} {$car->vehiclestitle} | ₹{$car->priceperday}/day | Fuel: {$car->fueltype} | Seats: {$car->seatingcapacity} | Year: {$car->modelyear}";
        }
        return implode("\n", $lines);
    } catch (Exception $e) {
        return "Could not load car inventory.";
    }
}

// ─── Call Gemini API ──────────────────────────────────────────────────
function callGeminiAPI($userMessage, $chatHistory, $carInventory) {
    if (GEMINI_API_KEY === 'YOUR_GEMINI_API_KEY_HERE') {
        return "⚠️ **Gemini API key not configured.**\n\nPlease open `carbot.php` and replace `YOUR_GEMINI_API_KEY_HERE` with your actual key from [Google AI Studio](https://aistudio.google.com/apikey).";
    }

    // Build the system instruction
    $systemInstruction = "You are CarBot, a friendly and helpful AI assistant for **Rent Wheels**, a car rental service.

IMPORTANT RULES:
- Always be helpful, polite, and concise.
- Use relevant emojis to make responses engaging but not excessive.
- Use **bold** for car names and key details.
- When mentioning the car listing page, link it as [Car Listing](car-listing.php).
- When mentioning booking, link as [Book Now](car-listing.php).
- When mentioning contact, link as [Contact Us](contact-us.php).
- When mentioning registration, link as [Register](register.php).
- Format lists with bullet points using •.
- Keep responses clear and structured.
- Only answer questions related to car rentals, the cars available, pricing, booking, or general automotive advice.
- If asked something completely unrelated, politely redirect to car rental topics.

CURRENT CAR INVENTORY (live from database):
{$carInventory}

Use this inventory to answer questions about available cars, pricing, fuel types, seating, etc. When recommending cars, reference actual cars from the inventory above.";

    // Build conversation turns for multi-turn context
    $contents = [];

    // Add previous chat history (last 6 exchanges to stay within token limits)
    $recentHistory = array_slice($chatHistory, -6);
    foreach ($recentHistory as $turn) {
        $contents[] = [
            "role" => "user",
            "parts" => [["text" => $turn['user']]]
        ];
        $contents[] = [
            "role" => "model",
            "parts" => [["text" => $turn['bot']]]
        ];
    }

    // Add current user message
    $contents[] = [
        "role" => "user",
        "parts" => [["text" => $userMessage]]
    ];

    $payload = json_encode([
        "system_instruction" => [
            "parts" => [["text" => $systemInstruction]]
        ],
        "contents" => $contents,
        "generationConfig" => [
            "temperature"     => 0.7,
            "maxOutputTokens" => 600,
            "topP"            => 0.9,
        ],
        "safetySettings" => [
            ["category" => "HARM_CATEGORY_HARASSMENT",        "threshold" => "BLOCK_MEDIUM_AND_ABOVE"],
            ["category" => "HARM_CATEGORY_HATE_SPEECH",       "threshold" => "BLOCK_MEDIUM_AND_ABOVE"],
            ["category" => "HARM_CATEGORY_SEXUALLY_EXPLICIT", "threshold" => "BLOCK_MEDIUM_AND_ABOVE"],
            ["category" => "HARM_CATEGORY_DANGEROUS_CONTENT", "threshold" => "BLOCK_MEDIUM_AND_ABOVE"],
        ]
    ]);

    $url = "https://generativelanguage.googleapis.com/v1beta/models/" . GEMINI_MODEL . ":generateContent?key=" . GEMINI_API_KEY;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT        => 20,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    // Handle curl errors
    if ($curlError) {
        return "🔌 **Connection error:** Could not reach Gemini API. Please check your internet connection.";
    }

    $data = json_decode($response, true);

    // Handle API errors
    if ($httpCode !== 200) {
        $errMsg = $data['error']['message'] ?? 'Unknown error';
        if ($httpCode === 400) return "⚠️ **API Error:** Invalid request — {$errMsg}";
        if ($httpCode === 401 || $httpCode === 403) return "🔑 **API Key Error:** Your Gemini API key is invalid or expired. Get a new one at [Google AI Studio](https://aistudio.google.com/apikey).";
        if ($httpCode === 429) return "⏳ **Rate limited:** Too many requests. Please wait a moment and try again.";
        return "❌ **Gemini API Error ({$httpCode}):** {$errMsg}";
    }

    // Extract text from response
    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

    if (!$text) {
        $finishReason = $data['candidates'][0]['finishReason'] ?? 'UNKNOWN';
        if ($finishReason === 'SAFETY') {
            return "🛡️ I couldn't respond to that due to safety guidelines. Please ask me something about car rentals!";
        }
        return "🤔 I didn't get a clear response. Could you rephrase your question?";
    }

    return trim($text);
}

// ── Handle POST ───────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['query'])) {
    $query       = trim($_POST['query']);
    $inventory   = getCarInventoryContext($dbh);
    $response    = callGeminiAPI($query, $_SESSION['chatHistory'], $inventory);
    $_SESSION['chatHistory'][] = ['user' => $query, 'bot' => $response];
    header('Location: carbot.php');
    exit;
}

$chatHistory = $_SESSION['chatHistory'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarBot — Rent Wheels AI Assistant</title>
    <meta name="description" content="Chat with CarBot, your Gemini-powered AI car rental assistant. Find the perfect vehicle, check prices, and get instant answers about Rent Wheels.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&family=Google+Sans+Text:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0d0d14;
            --bg-secondary: #13131f;
            --bg-surface: #1a1a2e;
            --bg-surface-2: #1e1e30;
            --border-subtle: rgba(255,255,255,0.07);
            --border-mid: rgba(255,255,255,0.12);

            --accent-blue: #4285F4;
            --accent-blue-glow: rgba(66,133,244,0.25);
            --accent-blue-soft: rgba(66,133,244,0.12);
            --accent-green: #34A853;
            --accent-yellow: #FBBC05;
            --accent-red: #EA4335;

            --gradient-gemini: linear-gradient(135deg, #4285F4 0%, #7B2FBE 35%, #EA4335 65%, #FBBC05 100%);
            --gradient-brand: linear-gradient(135deg, #4285F4, #5E97F6);
            --gradient-bg: radial-gradient(ellipse at 20% 20%, rgba(66,133,244,0.08) 0%, transparent 50%),
                           radial-gradient(ellipse at 80% 80%, rgba(123,47,190,0.06) 0%, transparent 50%),
                           #0d0d14;

            --text-primary: rgba(255,255,255,0.93);
            --text-secondary: rgba(255,255,255,0.55);
            --text-tertiary: rgba(255,255,255,0.32);

            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --radius-xl: 28px;

            --font-main: 'Google Sans', 'Google Sans Text', system-ui, sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-main);
            background: var(--gradient-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: var(--text-primary);
        }

        /* ── Animated background orbs ── */
        .bg-orbs { position: fixed; inset: 0; pointer-events: none; z-index: 0; overflow: hidden; }
        .orb {
            position: absolute; border-radius: 50%;
            filter: blur(80px); opacity: 0.4;
            animation: drift 20s ease-in-out infinite;
        }
        .orb-1 { width: 500px; height: 500px; background: radial-gradient(circle, rgba(66,133,244,0.3), transparent); top: -150px; left: -100px; animation-delay: 0s; }
        .orb-2 { width: 400px; height: 400px; background: radial-gradient(circle, rgba(123,47,190,0.25), transparent); bottom: -100px; right: -80px; animation-delay: -8s; }
        .orb-3 { width: 300px; height: 300px; background: radial-gradient(circle, rgba(52,168,83,0.15), transparent); top: 50%; left: 50%; transform: translate(-50%,-50%); animation-delay: -14s; }
        @keyframes drift {
            0%,100% { transform: translate(0,0) scale(1); }
            33%  { transform: translate(30px,-20px) scale(1.05); }
            66%  { transform: translate(-20px,15px) scale(0.97); }
        }

        /* ── Topbar ── */
        .topbar {
            position: sticky; top: 0; z-index: 100;
            background: rgba(13,13,20,0.85);
            backdrop-filter: blur(20px) saturate(1.5);
            -webkit-backdrop-filter: blur(20px) saturate(1.5);
            border-bottom: 1px solid var(--border-subtle);
            padding: 0 28px; height: 60px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .topbar-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .brand-icon {
            width: 34px; height: 34px;
            background: var(--gradient-brand);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            box-shadow: 0 4px 14px rgba(66,133,244,0.4);
        }
        .brand-name { font-size: 1rem; font-weight: 700; color: var(--text-primary); letter-spacing: -0.01em; }
        .topbar-nav { display: flex; align-items: center; gap: 4px; }
        .topbar-nav a {
            color: var(--text-secondary); text-decoration: none;
            font-size: 0.875rem; font-weight: 500;
            padding: 6px 14px; border-radius: var(--radius-sm);
            transition: all 0.18s ease;
        }
        .topbar-nav a:hover { color: var(--text-primary); background: rgba(255,255,255,0.07); }
        .topbar-nav a.active { color: var(--accent-blue); background: var(--accent-blue-soft); }

        /* ── Page layout ── */
        .page-wrapper {
            flex: 1; display: flex;
            align-items: stretch; justify-content: center;
            padding: 28px 20px;
            position: relative; z-index: 1;
            min-height: calc(100vh - 60px);
        }
        .chat-layout {
            width: 100%; max-width: 820px;
            display: flex; flex-direction: column; gap: 0;
        }

        /* ── Hero header ── */
        .chat-hero {
            display: flex; align-items: center; gap: 20px;
            padding: 28px 32px 24px;
            background: linear-gradient(135deg, rgba(66,133,244,0.08) 0%, rgba(123,47,190,0.06) 50%, rgba(66,133,244,0.05) 100%);
            border: 1px solid rgba(66,133,244,0.2);
            border-bottom: none;
            border-radius: var(--radius-xl) var(--radius-xl) 0 0;
            position: relative; overflow: hidden;
        }
        .chat-hero::before {
            content: '';
            position: absolute; top: 0; left: -100%; right: 0; height: 2px;
            background: var(--gradient-gemini);
            animation: shimmer-bar 3s linear infinite;
            width: 300%;
        }
        @keyframes shimmer-bar {
            0%   { transform: translateX(0); }
            100% { transform: translateX(50%); }
        }

        .bot-avatar {
            width: 60px; height: 60px; border-radius: 18px;
            background: linear-gradient(135deg, #1a73e8, #7B2FBE);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; flex-shrink: 0; position: relative;
            box-shadow: 0 8px 24px rgba(66,133,244,0.4);
        }
        .bot-avatar::after {
            content: ''; position: absolute; bottom: -3px; right: -3px;
            width: 16px; height: 16px;
            background: var(--accent-green);
            border: 2.5px solid var(--bg-primary); border-radius: 50%;
            animation: pulse-dot 2.5s ease-in-out infinite;
        }
        @keyframes pulse-dot {
            0%,100% { box-shadow: 0 0 0 0 rgba(52,168,83,0.6); }
            50%     { box-shadow: 0 0 0 5px rgba(52,168,83,0); }
        }

        .hero-text h1 { font-size: 1.35rem; font-weight: 700; color: var(--text-primary); letter-spacing: -0.02em; margin-bottom: 4px; }
        .hero-text .subtitle { font-size: 0.83rem; color: var(--text-secondary); display: flex; align-items: center; gap: 6px; }
        .status-dot { width: 7px; height: 7px; background: var(--accent-green); border-radius: 50%; display: inline-block; animation: blink-soft 2.5s ease-in-out infinite; }
        @keyframes blink-soft { 0%,100% { opacity: 1; } 50% { opacity: 0.4; } }

        .hero-badges { margin-left: auto; display: flex; gap: 8px; align-items: center; }
        .hero-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem; font-weight: 600;
            display: flex; align-items: center; gap: 5px; white-space: nowrap;
        }
        .badge-gemini {
            background: linear-gradient(135deg, rgba(66,133,244,0.12), rgba(123,47,190,0.12));
            border: 1px solid rgba(123,47,190,0.3);
            color: #a78bfa;
        }
        .badge-live {
            background: rgba(52,168,83,0.1);
            border: 1px solid rgba(52,168,83,0.25);
            color: #34A853;
        }

        /* ── Chat messages ── */
        #chat-box {
            background: var(--bg-secondary);
            border-left: 1px solid var(--border-subtle);
            border-right: 1px solid var(--border-subtle);
            padding: 24px;
            min-height: 420px; max-height: 460px;
            overflow-y: auto;
            display: flex; flex-direction: column; gap: 20px;
            scrollbar-width: thin;
            scrollbar-color: rgba(66,133,244,0.25) transparent;
        }
        #chat-box::-webkit-scrollbar { width: 4px; }
        #chat-box::-webkit-scrollbar-thumb { background: rgba(66,133,244,0.25); border-radius: 2px; }

        /* Empty state */
        .empty-state {
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            flex: 1; min-height: 360px; gap: 16px; text-align: center;
        }
        .empty-icon {
            width: 84px; height: 84px; border-radius: 24px;
            background: linear-gradient(135deg, rgba(66,133,244,0.15), rgba(123,47,190,0.12));
            border: 1px solid rgba(66,133,244,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 2.4rem; margin-bottom: 4px;
            animation: float 4s ease-in-out infinite;
        }
        @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
        .empty-state h2 { font-size: 1.2rem; font-weight: 600; color: var(--text-primary); letter-spacing: -0.01em; }
        .empty-state p { font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6; max-width: 340px; }
        .gemini-tag {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 12px; border-radius: 12px;
            background: linear-gradient(135deg, rgba(66,133,244,0.1), rgba(123,47,190,0.1));
            border: 1px solid rgba(123,47,190,0.2);
            font-size: 0.75rem; font-weight: 600; color: #a78bfa;
            margin-top: 4px;
        }

        /* Message rows */
        .msg-row { display: flex; align-items: flex-end; gap: 10px; animation: msg-in 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .msg-row.user { flex-direction: row-reverse; }
        @keyframes msg-in { from { opacity:0; transform: translateY(10px) scale(0.97); } to { opacity:1; transform: translateY(0) scale(1); } }

        .msg-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; flex-shrink: 0;
        }
        .msg-avatar.bot { background: linear-gradient(135deg, #1a73e8, #7B2FBE); box-shadow: 0 2px 10px rgba(66,133,244,0.35); }
        .msg-avatar.user { background: linear-gradient(135deg, #5E97F6, #4285F4); }

        .bubble {
            max-width: 76%; padding: 13px 17px;
            border-radius: 18px; font-size: 0.9rem; line-height: 1.7;
            word-break: break-word;
        }
        .bubble.bot-bubble {
            background: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            border-bottom-left-radius: 5px;
        }
        .bubble.user-bubble {
            background: linear-gradient(135deg, #1a73e8, #1558b0);
            color: white; border-bottom-right-radius: 5px;
            box-shadow: 0 4px 16px rgba(26,115,232,0.3);
        }
        .bubble a { color: #5E97F6; text-decoration: underline; text-underline-offset: 2px; font-weight: 500; }
        .bubble strong { color: rgba(255,255,255,0.95); }
        .user-bubble a { color: rgba(255,255,255,0.85); }

        /* Typing indicator */
        .typing-row { display: flex; align-items: flex-end; gap: 10px; }
        .typing-bubble {
            background: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 18px; border-bottom-left-radius: 5px;
            padding: 14px 18px;
            display: flex; align-items: center; gap: 5px;
        }
        .typing-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: rgba(66,133,244,0.6);
            animation: typing-bounce 1.2s ease-in-out infinite;
        }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typing-bounce {
            0%,60%,100% { transform: translateY(0); opacity: 0.4; }
            30% { transform: translateY(-6px); opacity: 1; }
        }

        /* ── Input area ── */
        .chat-input-area {
            background: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-top: 1px solid rgba(66,133,244,0.1);
            border-radius: 0 0 var(--radius-xl) var(--radius-xl);
            padding: 20px 24px 24px;
        }
        .input-container {
            display: flex; gap: 10px; align-items: flex-end;
            background: var(--bg-secondary);
            border: 1.5px solid var(--border-mid);
            border-radius: var(--radius-lg);
            padding: 10px 12px 10px 18px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .input-container:focus-within {
            border-color: rgba(66,133,244,0.5);
            box-shadow: 0 0 0 3px rgba(66,133,244,0.1);
        }
        #msgInput {
            flex: 1; background: none; border: none; outline: none;
            color: var(--text-primary); font-family: var(--font-main);
            font-size: 0.92rem; line-height: 1.5; resize: none;
            min-height: 24px; max-height: 120px; padding: 2px 0;
        }
        #msgInput::placeholder { color: var(--text-tertiary); }

        .btn-send {
            width: 38px; height: 38px; border-radius: 10px;
            background: var(--gradient-brand);
            border: none; color: white; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s ease; flex-shrink: 0;
            box-shadow: 0 2px 10px rgba(66,133,244,0.35);
        }
        .btn-send:hover { transform: scale(1.06); box-shadow: 0 4px 16px rgba(66,133,244,0.5); }
        .btn-send:active { transform: scale(0.96); }
        .btn-send:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        .btn-send .material-symbols-rounded { font-size: 20px; font-variation-settings: 'FILL' 1; }

        .input-footer { display: flex; align-items: center; justify-content: space-between; margin-top: 12px; }
        .input-hint { font-size: 0.75rem; color: var(--text-tertiary); display: flex; align-items: center; gap: 5px; }
        .input-hint .material-symbols-rounded { font-size: 14px; }
        .btn-clear {
            background: transparent; border: 1px solid var(--border-mid);
            color: var(--text-tertiary); border-radius: var(--radius-sm);
            padding: 5px 13px; font-size: 0.75rem; font-family: var(--font-main);
            cursor: pointer; display: flex; align-items: center; gap: 5px;
            transition: all 0.18s ease;
        }
        .btn-clear:hover { border-color: rgba(234,67,53,0.4); color: #EA4335; background: rgba(234,67,53,0.06); }
        .btn-clear .material-symbols-rounded { font-size: 14px; }

        /* ── Suggestions ── */
        .suggestions-label {
            font-size: 0.75rem; color: var(--text-tertiary); font-weight: 500;
            margin-top: 16px; margin-bottom: 8px;
            letter-spacing: 0.05em; text-transform: uppercase;
        }
        .chips-row { display: flex; flex-wrap: wrap; gap: 8px; }
        .chip {
            background: rgba(66,133,244,0.07);
            border: 1px solid rgba(66,133,244,0.18);
            color: rgba(255,255,255,0.7);
            border-radius: 20px; padding: 6px 15px;
            font-size: 0.8rem; font-family: var(--font-main);
            cursor: pointer; transition: all 0.18s ease; font-weight: 500;
        }
        .chip:hover { background: rgba(66,133,244,0.16); border-color: rgba(66,133,244,0.4); color: white; transform: translateY(-1px); }
        .chip:active { transform: translateY(0); }

        /* ── Responsive ── */
        @media (max-width: 600px) {
            .topbar { padding: 0 16px; }
            .topbar-nav a { padding: 5px 10px; font-size: 0.8rem; }
            .page-wrapper { padding: 0; }
            .chat-layout { max-width: 100%; }
            .chat-hero { border-radius: 0; padding: 20px 18px 18px; }
            .chat-input-area { border-radius: 0; }
            .hero-badges { display: none; }
            .bubble { max-width: 88%; }
        }
    </style>
</head>
<body>

<!-- Background orbs -->
<div class="bg-orbs" aria-hidden="true">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>

<!-- Topbar -->
<header class="topbar">
    <a class="topbar-brand" href="index.php" aria-label="Rent Wheels Home">
        <div class="brand-icon">🚗</div>
        <span class="brand-name">Rent Wheels</span>
    </a>
    <nav class="topbar-nav" aria-label="Main navigation">
        <a href="index.php">Home</a>
        <a href="car-listing.php">Cars</a>
        <a href="carbot.php" class="active">CarBot</a>
        <a href="page.php?type=aboutus">About</a>
        <a href="contact-us.php">Contact</a>
    </nav>
</header>

<!-- Page content -->
<main class="page-wrapper">
    <div class="chat-layout">

        <!-- Hero header -->
        <div class="chat-hero">
            <div class="bot-avatar" role="img" aria-label="CarBot AI assistant">🤖</div>
            <div class="hero-text">
                <h1>CarBot</h1>
                <div class="subtitle">
                    <span class="status-dot"></span>
                    AI Car Rental Assistant &middot; Always Online
                </div>
            </div>
            <div class="hero-badges">
                <div class="hero-badge badge-gemini">✦ Gemini AI</div>
                <div class="hero-badge badge-live">● Live Fleet</div>
            </div>
        </div>

        <!-- Messages -->
        <div id="chat-box" role="log" aria-live="polite" aria-label="Chat messages">
            <?php if (empty($chatHistory)): ?>
            <div class="empty-state">
                <div class="empty-icon" aria-hidden="true">🚘</div>
                <h2>How can I help you today?</h2>
                <p>I'm CarBot — powered by Google Gemini. Ask me anything about our cars, pricing, fuel types, or how to book!</p>
                <span class="gemini-tag">✦ Powered by Gemini AI · Real-time fleet data</span>
            </div>
            <?php else: ?>
                <?php foreach ($chatHistory as $chat): ?>
                <div class="msg-row user">
                    <div class="bubble user-bubble"><?php echo nl2br(htmlspecialchars($chat['user'])); ?></div>
                    <div class="msg-avatar user" aria-hidden="true">👤</div>
                </div>
                <div class="msg-row bot">
                    <div class="msg-avatar bot" aria-hidden="true">🤖</div>
                    <div class="bubble bot-bubble"><?php
                        $text = htmlspecialchars($chat['bot']);
                        $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
                        $text = preg_replace('/\[([^\]]+)\]\(([^\)]+)\)/', '<a href="$2">$1</a>', $text);
                        echo nl2br($text);
                    ?></div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Input area -->
        <div class="chat-input-area">
            <form method="post" id="chatForm">
                <div class="input-container">
                    <textarea
                        name="query"
                        id="msgInput"
                        placeholder="Ask Gemini about cars, pricing, fuel types…"
                        rows="1"
                        required
                        aria-label="Message input"
                    ></textarea>
                    <button type="submit" id="sendBtn" class="btn-send" title="Send message" aria-label="Send">
                        <span class="material-symbols-rounded">send</span>
                    </button>
                </div>
                <div class="input-footer">
                    <span class="input-hint">
                        <span class="material-symbols-rounded">keyboard_return</span>
                        Press Enter to send · Shift+Enter for new line
                    </span>
                    <button type="submit" name="clear_chat" class="btn-clear" form="clearForm" aria-label="Clear chat">
                        <span class="material-symbols-rounded">delete_sweep</span>
                        Clear chat
                    </button>
                </div>
            </form>

            <form id="clearForm" method="post" aria-hidden="true">
                <input type="hidden" name="clear_chat" value="1">
            </form>

            <?php if (empty($chatHistory)): ?>
            <div role="group" aria-label="Quick suggestion prompts">
                <p class="suggestions-label">Suggested questions</p>
                <div class="chips-row">
                    <button class="chip" onclick="ask(this)" type="button">🚗 Show all cars</button>
                    <button class="chip" onclick="ask(this)" type="button">💰 Cheapest car today</button>
                    <button class="chip" onclick="ask(this)" type="button">⛽ Petrol vs CNG</button>
                    <button class="chip" onclick="ask(this)" type="button">👨‍👩‍👧 Best family car</button>
                    <button class="chip" onclick="ask(this)" type="button">📅 How to book a car</button>
                    <button class="chip" onclick="ask(this)" type="button">🛣️ Best road trip car</button>
                    <button class="chip" onclick="ask(this)" type="button">✨ Luxury cars available</button>
                    <button class="chip" onclick="ask(this)" type="button">🌿 Eco-friendly options</button>
                </div>
            </div>
            <?php endif; ?>
        </div>

    </div>
</main>

<script>
    // Auto-scroll to bottom of chat
    const chatBox = document.getElementById('chat-box');
    if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;

    // Show typing indicator and disable send while submitting
    document.getElementById('chatForm').addEventListener('submit', function (e) {
        const input = document.getElementById('msgInput');
        if (!input.value.trim()) { e.preventDefault(); return; }

        // Disable send button to prevent double submit
        const btn = document.getElementById('sendBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-rounded" style="animation:spin 1s linear infinite">progress_activity</span>';

        // Append typing indicator to chat box
        if (chatBox) {
            const typingRow = document.createElement('div');
            typingRow.className = 'typing-row';
            typingRow.id = 'typing-indicator';
            typingRow.innerHTML = `
                <div class="msg-avatar bot" aria-hidden="true">🤖</div>
                <div class="typing-bubble">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>`;
            chatBox.appendChild(typingRow);
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });

    // Chip click — strip emoji prefix, send clean query
    function ask(btn) {
        const raw = btn.textContent.trim();
        const clean = raw.replace(/^[\u{1F000}-\u{1FFFF}\u{2600}-\u{27BF}\u{2300}-\u{23FF}\u{1F300}-\u{1F9FF}\uFE0F\s]+/u, '').trim();
        document.getElementById('msgInput').value = clean || raw;
        document.getElementById('chatForm').submit();
    }

    // Auto-resize textarea as user types
    const textarea = document.getElementById('msgInput');
    textarea.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    // Enter to send (Shift+Enter = new line)
    textarea.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (this.value.trim()) document.getElementById('chatForm').submit();
        }
    });
</script>

<style>
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>
</body>
</html>
