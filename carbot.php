<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Initialize chat history in session
if (!isset($_SESSION['chatHistory'])) {
    $_SESSION['chatHistory'] = [];
}

// Function to create a chat session
function createChatSession($apiKey, $externalUserId) {
    $url = 'https://api.on-demand.io/chat/v1/sessions';
    $headers = [
        'Content-Type: application/json',
        'apikey: ' . $apiKey
    ];
    $body = json_encode([
        'pluginIds' => [],
        'externalUserId' => $externalUserId
    ]);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// Function to submit a query
function submitQuery($apiKey, $sessionId, $query) {
    $url = 'https://api.on-demand.io/chat/v1/sessions/' . $sessionId . '/query';
    $headers = [
        'Content-Type: application/json',
        'apikey: ' . $apiKey
    ];
    $body = json_encode([
        'endpointId' => 'predefined-openai-gpt4o',
        'query' => $query,
        'pluginIds' => ['plugin-1712327325', 'plugin-1713962163', 'plugin-1730058905'],
        'responseMode' => 'sync'
    ]);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

$apiKey = 'NSlWKonOLdreYgztQWOY1xJC1FSSY91f';
$externalUserId = 'guest_' . session_id();
$responseMessage = '';
$errorMessage = '';

// Clear chat
if (isset($_POST['clear_chat'])) {
    $_SESSION['chatHistory'] = [];
    header('Location: carbot.php');
    exit;
}

// Handle message submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['query'])) {
    $query = htmlspecialchars(trim($_POST['query']));

    // Create session
    $sessionResponse = createChatSession($apiKey, $externalUserId);

    if (isset($sessionResponse['data']['id'])) {
        $sessionId = $sessionResponse['data']['id'];
        $queryResponse = submitQuery($apiKey, $sessionId, $query);

        if (isset($queryResponse['data']['answer'])) {
            $responseMessage = $queryResponse['data']['answer'];
            $_SESSION['chatHistory'][] = ['user' => $query, 'bot' => $responseMessage];
        } else {
            $errorMessage = 'Bot error: ' . (isset($queryResponse['message']) ? $queryResponse['message'] : 'Unknown error');
            $_SESSION['chatHistory'][] = ['user' => $query, 'bot' => '⚠️ Sorry, I could not get a response. Please try again.'];
        }
    } else {
        $_SESSION['chatHistory'][] = ['user' => $query, 'bot' => '⚠️ Could not connect to AI service. Please try again later.'];
    }

    // Redirect to avoid form resubmission
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
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            min-height: 100vh;
            color: white;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .carbot-nav {
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .carbot-nav .brand { font-size: 1.4rem; font-weight: 700; color: #f5a623; }
        .carbot-nav a { color: rgba(255,255,255,0.8); text-decoration: none; margin-left: 20px; font-size: 0.9rem; transition: color 0.2s; }
        .carbot-nav a:hover { color: #f5a623; }

        /* Main layout */
        .carbot-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        #chat-wrapper {
            width: 100%;
            max-width: 700px;
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        /* Header */
        .chat-header {
            background: linear-gradient(135deg, #f5a623, #e8860c);
            border-radius: 18px 18px 0 0;
            padding: 20px 25px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .chat-header .bot-avatar {
            width: 50px; height: 50px;
            background: white;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .chat-header h1 { font-size: 1.4rem; font-weight: 700; margin: 0; color: white; }
        .chat-header p { font-size: 0.85rem; color: rgba(255,255,255,0.85); margin: 2px 0 0; }
        .online-dot { width: 10px; height: 10px; background: #4ade80; border-radius: 50%; display: inline-block; margin-right: 5px; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }

        /* Chat history */
        #chat-history {
            background: rgba(15, 12, 41, 0.85);
            backdrop-filter: blur(10px);
            padding: 20px;
            min-height: 380px;
            max-height: 420px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
            scrollbar-width: thin;
            scrollbar-color: rgba(245,166,35,0.4) transparent;
        }
        #chat-history::-webkit-scrollbar { width: 5px; }
        #chat-history::-webkit-scrollbar-thumb { background: rgba(245,166,35,0.4); border-radius: 3px; }

        /* Welcome message */
        .welcome-msg {
            text-align: center;
            padding: 40px 20px;
            color: rgba(255,255,255,0.5);
        }
        .welcome-msg .icon { font-size: 3rem; margin-bottom: 10px; }
        .welcome-msg p { font-size: 0.95rem; }

        /* Message bubbles */
        .msg-row { display: flex; align-items: flex-end; gap: 10px; }
        .msg-row.user { flex-direction: row-reverse; }

        .avatar-sm {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; flex-shrink: 0;
        }
        .avatar-sm.bot { background: linear-gradient(135deg, #f5a623, #e8860c); }
        .avatar-sm.user { background: linear-gradient(135deg, #667eea, #764ba2); }

        .bubble {
            max-width: 75%;
            padding: 12px 16px;
            border-radius: 18px;
            font-size: 0.93rem;
            line-height: 1.55;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

        .bubble.bot {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.1);
            border-bottom-left-radius: 4px;
            color: rgba(255,255,255,0.92);
        }
        .bubble.user {
            background: linear-gradient(135deg, #f5a623, #e8860c);
            border-bottom-right-radius: 4px;
            color: white;
        }

        /* Input area */
        .chat-input-area {
            background: rgba(15, 12, 41, 0.95);
            border-top: 1px solid rgba(255,255,255,0.08);
            border-radius: 0 0 18px 18px;
            padding: 18px 20px;
        }
        .input-row {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }
        .input-row textarea {
            flex: 1;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 12px;
            color: white;
            padding: 12px 15px;
            font-size: 0.93rem;
            resize: none;
            height: 50px;
            transition: border-color 0.2s, height 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .input-row textarea:focus {
            outline: none;
            border-color: #f5a623;
            background: rgba(255,255,255,0.12);
        }
        .input-row textarea::placeholder { color: rgba(255,255,255,0.35); }

        .btn-send {
            background: linear-gradient(135deg, #f5a623, #e8860c);
            border: none;
            border-radius: 12px;
            color: white;
            width: 50px; height: 50px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: transform 0.2s, opacity 0.2s;
            flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
        }
        .btn-send:hover { opacity: 0.9; transform: scale(1.05); }
        .btn-send:active { transform: scale(0.97); }

        .chat-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        .chat-footer small { color: rgba(255,255,255,0.3); font-size: 0.78rem; }
        .btn-clear {
            background: none;
            border: 1px solid rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.5);
            border-radius: 8px;
            padding: 4px 12px;
            font-size: 0.78rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-clear:hover { border-color: #f5a623; color: #f5a623; }

        /* Suggestions */
        .suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }
        .suggestion-btn {
            background: rgba(245,166,35,0.12);
            border: 1px solid rgba(245,166,35,0.3);
            color: rgba(255,255,255,0.8);
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .suggestion-btn:hover { background: rgba(245,166,35,0.25); color: white; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="carbot-nav">
    <span class="brand">🚗 Rent Wheels</span>
    <div>
        <a href="index.php">Home</a>
        <a href="car-listing.php">Cars</a>
        <a href="page.php?type=aboutus">About</a>
        <a href="contact-us.php">Contact</a>
    </div>
</nav>

<div class="carbot-main">
    <div id="chat-wrapper">

        <!-- Chat Header -->
        <div class="chat-header">
            <div class="bot-avatar">🤖</div>
            <div>
                <h1>CarBot</h1>
                <p><span class="online-dot"></span>Your AI Car Rental Assistant</p>
            </div>
        </div>

        <!-- Chat History -->
        <div id="chat-history">
            <?php if (empty($chatHistory)): ?>
            <div class="welcome-msg">
                <div class="icon">🚘</div>
                <p>Hi! I'm CarBot. Ask me anything about cars, rentals, fuel types, or pricing!</p>
            </div>
            <?php else: ?>
                <?php foreach ($chatHistory as $chat): ?>
                <div class="msg-row user">
                    <div class="bubble user"><?php echo nl2br(htmlspecialchars($chat['user'])); ?></div>
                    <div class="avatar-sm user">👤</div>
                </div>
                <div class="msg-row">
                    <div class="avatar-sm bot">🤖</div>
                    <div class="bubble bot"><?php echo nl2br(htmlspecialchars($chat['bot'])); ?></div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Input Area -->
        <div class="chat-input-area">
            <form method="post" id="chatForm">
                <div class="input-row">
                    <textarea name="query" id="queryInput" placeholder="Ask about cars, pricing, fuel types..." required></textarea>
                    <button type="submit" class="btn-send" title="Send">➤</button>
                </div>
                <div class="chat-footer">
                    <small>Powered by GPT-4o</small>
                    <button type="submit" name="clear_chat" class="btn-clear" form="clearForm">🗑 Clear chat</button>
                </div>
            </form>
            <form id="clearForm" method="post">
                <input type="hidden" name="clear_chat" value="1">
            </form>

            <!-- Quick suggestions -->
            <?php if (empty($chatHistory)): ?>
            <div class="suggestions">
                <button class="suggestion-btn" onclick="fillQuery(this)">Best cars under ₹1500/day</button>
                <button class="suggestion-btn" onclick="fillQuery(this)">Difference between petrol and CNG?</button>
                <button class="suggestion-btn" onclick="fillQuery(this)">Which car has best mileage?</button>
                <button class="suggestion-btn" onclick="fillQuery(this)">SUV vs Sedan for road trips</button>
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
// Auto-scroll chat to bottom
const hist = document.getElementById('chat-history');
if (hist) hist.scrollTop = hist.scrollHeight;

// Fill query from suggestion button
function fillQuery(btn) {
    document.getElementById('queryInput').value = btn.textContent;
    document.getElementById('queryInput').focus();
}

// Send on Enter (Shift+Enter for newline)
document.getElementById('queryInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('chatForm').submit();
    }
});
</script>

</body>
</html>
