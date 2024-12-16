


<!-- <?php
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

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// API key and external user ID
$apiKey = 'NSlWKonOLdreYgztQWOY1xJC1FSSY91f';
$externalUserId = 'guest_user';

// Initialize variables for response handling
$responseMessage = '';
$chatHistory = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['query'])) {
    $sessionResponse = createChatSession($apiKey, $externalUserId);
    $sessionId = $sessionResponse['data']['id'];
    $query = htmlspecialchars(trim($_POST['query']));
    $queryResponse = submitQuery($apiKey, $sessionId, $query);

    if (isset($queryResponse['data']['answer'])) {
        $responseMessage = $queryResponse['data']['answer'];
        $chatHistory[] = ['user' => $query, 'bot' => $responseMessage];
    } else {
        $responseMessage = 'Error: ' . $queryResponse['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Chatbot</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
            position: relative;
            overflow: hidden;
            color: #333;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('Automotive-chatbot-cover.jpg');
            background-size: cover;
            background-position: center;
            filter: blur(10px) brightness(0.7);
            z-index: -1;
        }

        #chat-container {
            max-width: 600px;
            margin: auto;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 25px;
            backdrop-filter: blur(5px);
        }

        h1 {
            text-align: center;
            color: #007BFF;
            font-size: 1.8em;
            margin-bottom: 20px;
        }

        .history {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f7f7f7;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.1);
        }

        .message {
            margin: 10px 0;
            display: flex;
        }

        .message.user {
            justify-content: flex-end;
            color: #007BFF;
        }

        .message.bot {
            justify-content: flex-start;
            color: #28a745;
        }

        .bubble {
            max-width: 70%;
            padding: 12px 18px;
            border-radius: 20px;
            font-size: 0.95em;
            line-height: 1.4;
            background: #e9f5ff;
        }

        .user .bubble {
            background-color: #007BFF;
            color: white;
            border-top-right-radius: 0;
        }

        .bot .bubble {
            background-color: #e0f7e4;
            color: #333;
            border-top-left-radius: 0;
        }

        textarea {
            width: 100%;
            height: 80px;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            resize: none;
            font-size: 1em;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s, box-shadow 0.3s;
            background-color: #fbfbfb;
            color: #333;
        }

        textarea:focus {
            outline: none;
            border-color: #007BFF;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
        }

        textarea::placeholder {
            color: #aaa;
            font-style: italic;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            font-size: 1.1em;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.1s;
        }

        button:hover {
            background-color: #0056b3;
        }

        button:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body>

<div class="collapse navbar-collapse" id="navigation" style="background-color: black; padding: 10px;">
    <ul class="nav navbar-nav" style="list-style-type: none; padding: 0; margin: 0; display: flex; justify-content: space-around;">
        <li style="margin-right: 20px;"><a href="index.php" style="color: white; text-decoration: none;">Home</a></li>
        <li style="margin-right: 20px;"><a href="page.php?type=aboutus" style="color: white; text-decoration: none;">About Us</a></li>
        <li style="margin-right: 20px;"><a href="carbot.php" style="color: white; text-decoration: none;">Car Bot</a></li>
        <li style="margin-right: 20px;"><a href="car-listing.php" style="color: white; text-decoration: none;">Car Listing</a></li>
        <li style="margin-right: 20px;"><a href="page.php?type=faqs" style="color: white; text-decoration: none;">FAQs</a></li>
        <li><a href="contact-us.php" style="color: white; text-decoration: none;">Contact Us</a></li>
    </ul>
</div>


    <div id="chat-container">
        <h1>Car Chatbot</h1>
        <div class="history">
            <?php if (!empty($chatHistory)): ?>
                <?php foreach ($chatHistory as $chat): ?>
                    <div class="message user"><div class="bubble"><?php echo $chat['user']; ?></div></div>
                    <div class="message bot"><div class="bubble"><?php echo $chat['bot']; ?></div></div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <form method="post">
            <textarea name="query" placeholder="Type your message here..." required></textarea>
            <button type="submit">Send</button>
        </form>
        <?php if ($responseMessage): ?>
            
        <?php endif; ?>
    </div>
</body>
</html> -->


 <!---- IMPROVED CSS OF BOT --->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Chatbot</title>
    
    <style>
        /*
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: url('https://source.unsplash.com/1600x900/?cars') no-repeat center center fixed;
            background-size: cover;
            backdrop-filter: blur(5px);
            color: white;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #chat-container {
            background: rgba(0, 0, 0, 0.6); 
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            padding: 20px;
            width: 90%;
            max-width: 600px;
            overflow: hidden;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2rem;
        }

        .history {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1); 
            border-radius: 5px;
        }

        .message {
            margin: 10px 0;
        }

        .message .bubble {
            padding: 10px;
            border-radius: 15px;
            max-width: 80%;
            word-wrap: break-word;
        }

        .message.user .bubble {
            background: #007bff; 
            color: white;
            align-self: flex-end;
        }

        .message.bot .bubble {
            background: #333; 
            color: white;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        textarea {
            resize: none;
            padding: 10px;
            border-radius: 5px;
            border: none;
            margin-bottom: 10px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        textarea::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: #007bff; 
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.1s;
        }

        button:hover {
            background-color: #0056b3; 
        }

        button:active {
            transform: scale(0.98); 
        }
            */

            /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    /* Combine an image and a gradient background with a blur effect */
    background: 
        linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
        url('https://source.unsplash.com/1600x900/?cars') no-repeat center center fixed;
    background-size: cover;
    backdrop-filter: blur(8px); /* Increase the blur effect for a more dramatic look */
    color: white;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

#chat-container {
    background: rgba(0, 0, 0, 0.7); /* Darker transparent black background for contrast */
    border-radius: 15px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
    padding: 25px;
    width: 90%;
    max-width: 650px;
    overflow: hidden;
    backdrop-filter: blur(5px); /* Add a mild blur effect to the chat container */
}

h1 {
    text-align: center;
    margin-bottom: 25px;
    font-size: 2.5rem;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7); /* Subtle shadow for title */
}

.history {
    max-height: 350px;
    overflow-y: auto;
    margin-bottom: 20px;
    padding: 15px;
    background: rgba(255, 255, 255, 0.1); /* Slight transparency for history */
    border-radius: 8px;
}

.message {
    margin: 15px 0;
}

.message .bubble {
    padding: 12px;
    border-radius: 20px;
    max-width: 85%;
    word-wrap: break-word;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2); /* Shadow for message bubbles */
}

.message.user .bubble {
    background: #007bff; /* Blue for user */
    color: white;
    align-self: flex-end;
}

.message.bot .bubble {
    background: #333; /* Dark grey for bot */
    color: white;
}

form {
    display: flex;
    flex-direction: column;
}

textarea {
    resize: none;
    padding: 12px;
    border-radius: 8px;
    border: none;
    margin-bottom: 15px;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    font-size: 1rem;
}

textarea::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

button {
    padding: 12px;
    border: none;
    border-radius: 8px;
    background: #007bff; /* Blue button */
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.1s;
}

button:hover {
    background-color: #0056b3; /* Darker blue on hover */
}

button:active {
    transform: scale(0.98); /* Slight shrink on click */
}

    </style>
</head>
<body>




    <div id="chat-container">
        <h1>Car Chatbot</h1>
        <div class="history">
            <?php if (!empty($chatHistory)): ?>
                <?php foreach ($chatHistory as $chat): ?>
                    <div class="message user">
                        <div class="bubble"><?php echo $chat['user']; ?></div>
                    </div>
                    <div class="message bot">
                        <div class="bubble"><?php echo $chat['bot']; ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <form method="post">
            <textarea name="query" placeholder="Type your message here..." required></textarea>
            <button type="submit">Send</button>
        </form>
    </div>
</body>
</html> 


<!---- -->

