<?php
session_start();
error_reporting(0);
include('includes/config.php');

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

// ─── Smart Car Bot Logic ───────────────────────────────────────────────
function getBotResponse($query, $dbh) {
    $q = strtolower(trim($query));

    // ── Greetings ──
    if (preg_match('/\b(hi|hello|hey|good morning|good evening|namaste|hola)\b/', $q)) {
        return "👋 Hello! Welcome to **Rent Wheels CarBot**! I can help you with:\n• 🚗 Finding the right car\n• 💰 Pricing & availability\n• ⛽ Fuel type comparisons\n• 📅 How to book a car\n\nWhat would you like to know?";
    }

    // ── Thanks ──
    if (preg_match('/\b(thanks|thank you|thankyou|ty|great|awesome|perfect)\b/', $q)) {
        return "😊 You're welcome! Happy to help. Anything else you'd like to know about our cars or rentals?";
    }

    // ── Goodbye ──
    if (preg_match('/\b(bye|goodbye|see you|exit|quit)\b/', $q)) {
        return "👋 Goodbye! Have a great journey! Come back anytime to rent a car with Rent Wheels. 🚗";
    }

    // ── List all cars ──
    if (preg_match('/\b(all cars|show cars|list cars|available cars|what cars|which cars|cars available|show me cars)\b/', $q)) {
        try {
            $sql = "SELECT tblvehicles.vehiclestitle, tblbrands.brandname, tblvehicles.priceperday, tblvehicles.fueltype, tblvehicles.modelyear, tblvehicles.seatingcapacity FROM tblvehicles JOIN tblbrands ON tblbrands.id = tblvehicles.vehiclesbrand ORDER BY tblvehicles.priceperday ASC";
            $res = $dbh->query($sql)->fetchAll(PDO::FETCH_OBJ);
            if ($res) {
                $reply = "🚗 **Available Cars at Rent Wheels:**\n\n";
                foreach ($res as $car) {
                    $reply .= "• **{$car->brandname} {$car->vehiclestitle}** — ₹{$car->priceperday}/day | {$car->fueltype} | {$car->seatingcapacity} seats | {$car->modelyear}\n";
                }
                $reply .= "\nVisit our [Car Listing](car-listing.php) to book!";
                return $reply;
            }
        } catch(Exception $e) {}
        return "Sorry, I couldn't fetch car data right now. Please visit our [Car Listing](car-listing.php) page!";
    }

    // ── Cheapest / Budget cars ──
    if (preg_match('/\b(cheap|budget|affordable|lowest|cheapest|price|economical|under)\b/', $q)) {
        try {
            $sql = "SELECT tblvehicles.vehiclestitle, tblbrands.brandname, tblvehicles.priceperday, tblvehicles.fueltype FROM tblvehicles JOIN tblbrands ON tblbrands.id = tblvehicles.vehiclesbrand ORDER BY tblvehicles.priceperday ASC LIMIT 3";
            $res = $dbh->query($sql)->fetchAll(PDO::FETCH_OBJ);
            if ($res) {
                $reply = "💰 **Most Affordable Cars:**\n\n";
                foreach ($res as $car) {
                    $reply .= "• **{$car->brandname} {$car->vehiclestitle}** — ₹{$car->priceperday}/day ({$car->fueltype})\n";
                }
                $reply .= "\nThese are our best value options!";
                return $reply;
            }
        } catch(Exception $e) {}
        return "💰 We have cars starting from ₹1200/day! Visit our [Car Listing](car-listing.php) to see all prices.";
    }

    // ── Most expensive / luxury / premium ──
    if (preg_match('/\b(luxury|premium|expensive|best|top|audi|bmw|high end|expensive)\b/', $q)) {
        try {
            $sql = "SELECT tblvehicles.vehiclestitle, tblbrands.brandname, tblvehicles.priceperday, tblvehicles.fueltype FROM tblvehicles JOIN tblbrands ON tblbrands.id = tblvehicles.vehiclesbrand ORDER BY tblvehicles.priceperday DESC LIMIT 3";
            $res = $dbh->query($sql)->fetchAll(PDO::FETCH_OBJ);
            if ($res) {
                $reply = "✨ **Premium / Luxury Cars:**\n\n";
                foreach ($res as $car) {
                    $reply .= "• **{$car->brandname} {$car->vehiclestitle}** — ₹{$car->priceperday}/day ({$car->fueltype})\n";
                }
                return $reply;
            }
        } catch(Exception $e) {}
        return "✨ We have premium cars including BMW and Audi! Check our [Car Listing](car-listing.php).";
    }

    // ── SUV ──
    if (preg_match('/\b(suv|crossover|tucson|rav4|seltos|cr-v|crv)\b/', $q)) {
        try {
            $sql = "SELECT tblvehicles.vehiclestitle, tblbrands.brandname, tblvehicles.priceperday, tblvehicles.fueltype, tblvehicles.seatingcapacity FROM tblvehicles JOIN tblbrands ON tblbrands.id = tblvehicles.vehiclesbrand WHERE LOWER(tblvehicles.vehiclestitle) LIKE '%tucson%' OR LOWER(tblvehicles.vehiclestitle) LIKE '%rav4%' OR LOWER(tblvehicles.vehiclestitle) LIKE '%seltos%' OR LOWER(tblvehicles.vehiclestitle) LIKE '%cr-v%' OR LOWER(tblvehicles.vehiclestitle) LIKE '%crv%' OR LOWER(tblvehicles.vehiclestitle) LIKE '%x1%'";
            $res = $dbh->query($sql)->fetchAll(PDO::FETCH_OBJ);
            if ($res && count($res) > 0) {
                $reply = "🚙 **Available SUVs:**\n\n";
                foreach ($res as $car) {
                    $reply .= "• **{$car->brandname} {$car->vehiclestitle}** — ₹{$car->priceperday}/day | {$car->seatingcapacity} seats\n";
                }
                $reply .= "\nSUVs are great for family trips and rough roads!";
                return $reply;
            }
        } catch(Exception $e) {}
        return "🚙 **Why choose an SUV?**\n• Higher ground clearance for rough roads\n• More boot space\n• Great for families (5-7 seats)\n• Better visibility\n\nCheck our available SUVs on the [Car Listing](car-listing.php) page!";
    }

    // ── Petrol cars ──
    if (preg_match('/\b(petrol|gasoline)\b/', $q)) {
        try {
            $sql = "SELECT tblvehicles.vehiclestitle, tblbrands.brandname, tblvehicles.priceperday FROM tblvehicles JOIN tblbrands ON tblbrands.id = tblvehicles.vehiclesbrand WHERE LOWER(tblvehicles.fueltype) = 'petrol' ORDER BY priceperday ASC";
            $res = $dbh->query($sql)->fetchAll(PDO::FETCH_OBJ);
            if ($res) {
                $reply = "⛽ **Available Petrol Cars:**\n\n";
                foreach ($res as $car) {
                    $reply .= "• **{$car->brandname} {$car->vehiclestitle}** — ₹{$car->priceperday}/day\n";
                }
                return $reply;
            }
        } catch(Exception $e) {}
        return "⛽ Petrol cars are widely available and easy to refuel anywhere. Check our [Car Listing](car-listing.php)!";
    }

    // ── CNG cars ──
    if (preg_match('/\b(cng|compressed natural gas)\b/', $q)) {
        try {
            $sql = "SELECT tblvehicles.vehiclestitle, tblbrands.brandname, tblvehicles.priceperday FROM tblvehicles JOIN tblbrands ON tblbrands.id = tblvehicles.vehiclesbrand WHERE LOWER(tblvehicles.fueltype) = 'cng'";
            $res = $dbh->query($sql)->fetchAll(PDO::FETCH_OBJ);
            if ($res && count($res) > 0) {
                $reply = "🌿 **Available CNG Cars (Eco-Friendly & Cheaper to Run):**\n\n";
                foreach ($res as $car) {
                    $reply .= "• **{$car->brandname} {$car->vehiclestitle}** — ₹{$car->priceperday}/day\n";
                }
                $reply .= "\n✅ CNG is ~40% cheaper than petrol per km!";
                return $reply;
            }
        } catch(Exception $e) {}
        return "🌿 **CNG Cars Benefits:**\n• ~40% cheaper than petrol\n• Eco-friendly (lower emissions)\n• Great for city driving\n\nCheck our [Car Listing](car-listing.php) for CNG options!";
    }

    // ── Fuel type comparison ──
    if (preg_match('/\b(petrol vs cng|cng vs petrol|difference.*fuel|fuel type|which fuel)\b/', $q)) {
        return "⛽ **Petrol vs CNG Comparison:**\n\n🔵 **Petrol:**\n• Available everywhere\n• Better performance\n• Higher running cost\n• Good for highways\n\n🟢 **CNG:**\n• ~40% cheaper per km\n• Eco-friendly\n• Best for city drives\n• Fewer refuelling stations\n\n**Recommendation:** Choose CNG for daily city commute, Petrol for long highway trips!";
    }

    // ── How to book ──
    if (preg_match('/\b(how to book|booking process|how do i rent|how to rent|steps to book|make.*booking|create.*booking)\b/', $q)) {
        return "📅 **How to Book a Car at Rent Wheels:**\n\n1️⃣ Go to [Car Listing](car-listing.php) and browse cars\n2️⃣ Click **\"View Details\"** on any car\n3️⃣ Select your **From Date** and **To Date**\n4️⃣ Add a message (optional)\n5️⃣ Click **\"Book Now\"**\n6️⃣ Wait for admin confirmation ✅\n\n💡 **Tip:** You need to be logged in to make a booking. [Register here](register.php)!";
    }

    // ── Price / cost ──
    if (preg_match('/\b(price|cost|rate|charge|fee|per day|daily rate|how much)\b/', $q)) {
        try {
            $min = $dbh->query("SELECT MIN(priceperday) FROM tblvehicles")->fetchColumn();
            $max = $dbh->query("SELECT MAX(priceperday) FROM tblvehicles")->fetchColumn();
            return "💰 **Pricing at Rent Wheels:**\n\n• Starting from: **₹{$min}/day**\n• Up to: **₹{$max}/day** (luxury cars)\n\n📋 Pricing depends on car model, fuel type, and year.\n\n👉 View all prices at [Car Listing](car-listing.php)";
        } catch(Exception $e) {}
        return "💰 Our cars start from ₹1200/day. Visit [Car Listing](car-listing.php) to see all prices!";
    }

    // ── Family car / seats ──
    if (preg_match('/\b(family|kids|children|group|5 seat|7 seat|spacious|large)\b/', $q)) {
        try {
            $sql = "SELECT tblvehicles.vehiclestitle, tblbrands.brandname, tblvehicles.priceperday, tblvehicles.seatingcapacity FROM tblvehicles JOIN tblbrands ON tblbrands.id = tblvehicles.vehiclesbrand WHERE tblvehicles.seatingcapacity >= 5 ORDER BY seatingcapacity DESC, priceperday ASC LIMIT 4";
            $res = $dbh->query($sql)->fetchAll(PDO::FETCH_OBJ);
            if ($res) {
                $reply = "👨‍👩‍👧‍👦 **Best Family Cars (5+ seats):**\n\n";
                foreach ($res as $car) {
                    $reply .= "• **{$car->brandname} {$car->vehiclestitle}** — ₹{$car->priceperday}/day | {$car->seatingcapacity} seats\n";
                }
                return $reply;
            }
        } catch(Exception $e) {}
        return "👨‍👩‍👧‍👦 For families, we recommend SUVs with 5+ seats. Check [Car Listing](car-listing.php)!";
    }

    // ── Road trip ──
    if (preg_match('/\b(road trip|highway|long drive|trip|travel|tour)\b/', $q)) {
        return "🛣️ **Best Cars for Road Trips:**\n\n• **SUVs** — Best comfort & boot space (Hyundai Tucson, Toyota RAV4)\n• **Sedans** — Fuel efficient on highways (Nissan Versa)\n• **Petrol** — Best for long highway drives\n\n**Tips for road trips:**\n✅ Check AC & power windows\n✅ Choose petrol for highways\n✅ Pick cars with 4+ seats for comfort\n\nBook your road trip car at [Car Listing](car-listing.php)!";
    }

    // ── Car not starting / trouble ──
    if (preg_match('/\b(car not starting|won\'t start|won\'t start|engine.*problem|battery dead|breakdown|trouble|help.*car)\b/', $q)) {
        return "🆘 **Car Trouble? Here's what to do:**\n\n🔋 **Car won't start:**\n• Check if battery is dead (jump start needed)\n• Check fuel level\n• Try neutral gear, then restart\n\n🚗 **If you rented from us:**\n• Call our support immediately\n• Don't attempt repairs yourself\n• We'll arrange a replacement car\n\n📞 **Contact us:** Visit our [Contact Page](contact-us.php) for immediate support!";
    }

    // ── AC / features ──
    if (preg_match('/\b(ac|air condition|features|amenities|comfort|power windows|airbag)\b/', $q)) {
        return "❄️ **Car Features Available at Rent Wheels:**\n\n✅ Air Conditioning (AC)\n✅ Power Windows\n✅ Power Steering\n✅ Driver & Passenger Airbags\n✅ Anti-lock Braking System (ABS)\n✅ Central Locking\n✅ Crash Sensor\n✅ Leather Seats (selected cars)\n\nAll features shown on each car's detail page. Visit [Car Listing](car-listing.php)!";
    }

    // ── Registration / login ──
    if (preg_match('/\b(register|sign up|signup|create account|login|log in|signin)\b/', $q)) {
        return "👤 **Account at Rent Wheels:**\n\n**To Register:**\n→ Click [Register](register.php) and fill in your details\n\n**To Login:**\n→ Click [Login](login.php) with your email & password\n\n**Why register?**\n✅ Book cars\n✅ View your bookings\n✅ Post testimonials\n✅ Manage your profile";
    }

    // ── My bookings ──
    if (preg_match('/\b(my booking|booking status|check booking|booking history)\b/', $q)) {
        return "📋 **Check Your Bookings:**\n\n→ Login to your account\n→ Click **\"My Bookings\"** in the menu\n\nYou'll see:\n• Booking status (Pending / Confirmed / Cancelled)\n• Vehicle details\n• Dates booked\n• Pay button for confirmed bookings\n\n[View My Bookings](my-booking.php)";
    }

    // ── Contact ──
    if (preg_match('/\b(contact|phone|email|address|reach|support|help)\b/', $q)) {
        return "📞 **Contact Rent Wheels:**\n\n→ Visit our [Contact Us](contact-us.php) page\n→ Fill in your query and we'll get back to you\n\nOur team is available to help with:\n• Booking issues\n• Car breakdowns\n• Payment queries\n• General enquiries";
    }

    // ── Honda ──
    if (preg_match('/\bhonda\b/', $q)) {
        return getBrandInfo($dbh, 'Honda');
    }
    // ── BMW ──
    if (preg_match('/\bbmw\b/', $q)) {
        return getBrandInfo($dbh, 'BMW');
    }
    // ── Audi ──
    if (preg_match('/\baudi\b/', $q)) {
        return getBrandInfo($dbh, 'Audi');
    }
    // ── Toyota ──
    if (preg_match('/\btoyota\b/', $q)) {
        return getBrandInfo($dbh, 'Toyota');
    }
    // ── Hyundai ──
    if (preg_match('/\bhyundai\b/', $q)) {
        return getBrandInfo($dbh, 'Hyundai');
    }
    // ── Kia ──
    if (preg_match('/\bkia\b/', $q)) {
        return getBrandInfo($dbh, 'Kia');
    }
    // ── Nissan ──
    if (preg_match('/\bnissan\b/', $q)) {
        return getBrandInfo($dbh, 'Nissan');
    }
    // ── Maruti ──
    if (preg_match('/\bmaruti\b/', $q)) {
        return getBrandInfo($dbh, 'Maruti');
    }

    // ── Default fallback ──
    return "🤔 I'm not sure about that, but I can help with:\n\n• 🚗 **\"Show all cars\"** — see available cars\n• 💰 **\"Cheapest car\"** — budget options\n• ⛽ **\"Petrol vs CNG\"** — fuel comparison\n• 📅 **\"How to book\"** — booking guide\n• 👨‍👩‍👧 **\"Family car\"** — spacious vehicles\n• 🛣️ **\"Road trip car\"** — best for long drives\n• 📞 **\"Contact\"** — get support\n\nOr just type your question!";
}

function getBrandInfo($dbh, $brand) {
    try {
        $sql = "SELECT tblvehicles.vehiclestitle, tblvehicles.priceperday, tblvehicles.fueltype, tblvehicles.modelyear FROM tblvehicles JOIN tblbrands ON tblbrands.id = tblvehicles.vehiclesbrand WHERE LOWER(tblbrands.brandname) = LOWER(?) ORDER BY priceperday ASC";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$brand]);
        $res = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($res && count($res) > 0) {
            $reply = "🚗 **{$brand} Cars at Rent Wheels:**\n\n";
            foreach ($res as $car) {
                $reply .= "• **{$car->vehiclestitle}** — ₹{$car->priceperday}/day | {$car->fueltype} | {$car->modelyear}\n";
            }
            $reply .= "\n[View Details & Book](car-listing.php)";
            return $reply;
        }
    } catch(Exception $e) {}
    return "We currently don't have {$brand} cars listed. Check our [Car Listing](car-listing.php) for all available vehicles!";
}

// ── Handle POST ───────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['query'])) {
    $query = trim($_POST['query']);
    $response = getBotResponse($query, $dbh);
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .nav {
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(10px);
            padding: 14px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .nav .brand { font-size: 1.2rem; font-weight: 700; color: #f5a623; text-decoration: none; }
        .nav-links a { color: rgba(255,255,255,0.75); text-decoration: none; margin-left: 22px; font-size: 0.88rem; transition: color 0.2s; }
        .nav-links a:hover { color: #f5a623; }

        /* Main */
        .main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 15px;
        }

        .chat-card {
            width: 100%;
            max-width: 720px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
        }

        /* Header */
        .chat-header {
            background: linear-gradient(135deg, #f5a623 0%, #e8860c 100%);
            padding: 22px 28px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .bot-icon {
            width: 54px; height: 54px;
            background: white;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }
        .chat-header h1 { font-size: 1.3rem; font-weight: 700; color: white; margin-bottom: 3px; }
        .chat-header p { font-size: 0.82rem; color: rgba(255,255,255,0.88); }
        .online { display: inline-block; width: 9px; height: 9px; background: #4ade80; border-radius: 50%; margin-right: 5px; animation: blink 2s infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

        /* Messages */
        #chat-box {
            background: #0f0c29;
            padding: 20px;
            min-height: 400px;
            max-height: 440px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 16px;
            scrollbar-width: thin;
            scrollbar-color: rgba(245,166,35,0.3) transparent;
        }
        #chat-box::-webkit-scrollbar { width: 5px; }
        #chat-box::-webkit-scrollbar-thumb { background: rgba(245,166,35,0.3); border-radius: 3px; }

        .welcome {
            text-align: center;
            padding: 50px 20px;
            color: rgba(255,255,255,0.4);
        }
        .welcome .big { font-size: 3rem; margin-bottom: 12px; }
        .welcome p { font-size: 0.95rem; line-height: 1.6; }

        /* Rows */
        .row { display: flex; align-items: flex-end; gap: 10px; }
        .row.user-row { flex-direction: row-reverse; }

        .av {
            width: 34px; height: 34px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.95rem; flex-shrink: 0;
        }
        .av.bot { background: linear-gradient(135deg, #f5a623, #e8860c); }
        .av.usr { background: linear-gradient(135deg, #667eea, #764ba2); }

        .bubble {
            max-width: 78%;
            padding: 12px 16px;
            border-radius: 18px;
            font-size: 0.9rem;
            line-height: 1.6;
            animation: pop 0.25s ease;
            word-break: break-word;
        }
        @keyframes pop { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:translateY(0)} }

        .bubble.bot-b {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.92);
            border-bottom-left-radius: 4px;
        }
        .bubble.usr-b {
            background: linear-gradient(135deg, #f5a623, #e8860c);
            color: white;
            border-bottom-right-radius: 4px;
        }
        .bubble a { color: #f5a623; }
        .bubble.bot-b a { color: #f5a623; }

        /* Input */
        .chat-input {
            background: #16122f;
            border-top: 1px solid rgba(255,255,255,0.07);
            padding: 18px 20px;
        }
        .input-row {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .input-row textarea {
            flex: 1;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 14px;
            color: white;
            padding: 12px 16px;
            font-size: 0.92rem;
            resize: none;
            height: 48px;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s;
            line-height: 1.4;
        }
        .input-row textarea:focus { outline: none; border-color: #f5a623; }
        .input-row textarea::placeholder { color: rgba(255,255,255,0.3); }
        .btn-send {
            background: linear-gradient(135deg, #f5a623, #e8860c);
            border: none;
            border-radius: 14px;
            width: 48px; height: 48px;
            color: white;
            font-size: 1.1rem;
            cursor: pointer;
            transition: transform 0.2s, opacity 0.2s;
            flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
        }
        .btn-send:hover { opacity: 0.88; transform: scale(1.05); }
        .btn-send:active { transform: scale(0.97); }

        .input-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        .input-meta small { color: rgba(255,255,255,0.25); font-size: 0.75rem; }
        .btn-clear {
            background: none;
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.4);
            border-radius: 8px;
            padding: 4px 12px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .btn-clear:hover { border-color: #e8490d; color: #e8490d; }

        /* Quick chips */
        .chips { display: flex; flex-wrap: wrap; gap: 7px; margin-top: 13px; }
        .chip {
            background: rgba(245,166,35,0.1);
            border: 1px solid rgba(245,166,35,0.25);
            color: rgba(255,255,255,0.75);
            border-radius: 20px;
            padding: 5px 13px;
            font-size: 0.78rem;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .chip:hover { background: rgba(245,166,35,0.22); color: white; border-color: #f5a623; }
    </style>
</head>
<body>

<nav class="nav">
    <a class="brand" href="index.php">🚗 Rent Wheels</a>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="car-listing.php">Cars</a>
        <a href="page.php?type=aboutus">About</a>
        <a href="contact-us.php">Contact</a>
    </div>
</nav>

<div class="main">
    <div class="chat-card">

        <div class="chat-header">
            <div class="bot-icon">🤖</div>
            <div>
                <h1>CarBot</h1>
                <p><span class="online"></span>Your AI Car Rental Assistant — Always Online</p>
            </div>
        </div>

        <div id="chat-box">
            <?php if (empty($chatHistory)): ?>
            <div class="welcome">
                <div class="big">🚘</div>
                <p>Hi! I'm <strong>CarBot</strong> — your smart car rental assistant.<br>
                Ask me about cars, pricing, fuel types, or how to book!</p>
            </div>
            <?php else: ?>
                <?php foreach ($chatHistory as $chat): ?>
                <div class="row user-row">
                    <div class="bubble usr-b"><?php echo nl2br(htmlspecialchars($chat['user'])); ?></div>
                    <div class="av usr">👤</div>
                </div>
                <div class="row">
                    <div class="av bot">🤖</div>
                    <div class="bubble bot-b"><?php
                        // Convert markdown-like **bold** and links
                        $text = htmlspecialchars($chat['bot']);
                        $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
                        $text = preg_replace('/\[([^\]]+)\]\(([^\)]+)\)/', '<a href="$2">$1</a>', $text);
                        echo nl2br($text);
                    ?></div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="chat-input">
            <form method="post" id="chatForm">
                <div class="input-row">
                    <textarea name="query" id="msgInput" placeholder="Ask about cars, pricing, fuel types..." required></textarea>
                    <button type="submit" class="btn-send" title="Send">➤</button>
                </div>
                <div class="input-meta">
                    <small>🚗 CarBot knows your fleet in real-time</small>
                    <button type="submit" name="clear_chat" class="btn-clear" form="clearForm">🗑 Clear</button>
                </div>
            </form>
            <form id="clearForm" method="post">
                <input type="hidden" name="clear_chat" value="1">
            </form>

            <?php if (empty($chatHistory)): ?>
            <div class="chips">
                <button class="chip" onclick="ask(this)">Show all cars</button>
                <button class="chip" onclick="ask(this)">Cheapest car</button>
                <button class="chip" onclick="ask(this)">Petrol vs CNG</button>
                <button class="chip" onclick="ask(this)">Family car</button>
                <button class="chip" onclick="ask(this)">How to book</button>
                <button class="chip" onclick="ask(this)">Road trip car</button>
                <button class="chip" onclick="ask(this)">BMW cars</button>
                <button class="chip" onclick="ask(this)">Luxury cars</button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Scroll to bottom
    const box = document.getElementById('chat-box');
    if (box) box.scrollTop = box.scrollHeight;

    // Chip click
    function ask(btn) {
        document.getElementById('msgInput').value = btn.textContent;
        document.getElementById('chatForm').submit();
    }

    // Enter to send
    document.getElementById('msgInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('chatForm').submit();
        }
    });
</script>
</body>
</html>
