<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/plain');

$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (empty($message)) {
    echo "Sorry, I didn't receive any message.";
    exit;
}

$message_lower = strtolower($message);

// âœ… 0. Identity-related questions
$identity_questions = ['what is your name', 'who are you', 'your name', 'what are you'];
foreach ($identity_questions as $phrase) {
    if (strpos($message_lower, $phrase) !== false) {
        echo "I am your AI assistant, trained by TextSnap.";
        exit;
    }
}

// âœ… 1. CEO-related questions
$ceo_keywords = ['textsnap ceo', 'ceo of textsnap', 'who is the ceo of textsnap', 'textsnap founder', 'founder of textsnap'];
foreach ($ceo_keywords as $phrase) {
    if (strpos($message_lower, $phrase) !== false) {
        echo "The CEO and Founder of TextSnap is Amit Das. He is a developer and entrepreneur focused on building tools that empower users to share and save text snippets efficiently.";
        exit;
    }
}

// âœ… 2. Greetings handling
$greetings = ['hi', 'hello', 'hey', 'good morning', 'good evening', 'good afternoon', 'yo', 'hola'];
foreach ($greetings as $greeting) {
    if (strpos($message_lower, $greeting) !== false && strlen($message_lower) <= 20) {
        $simple_api_url = 'https://api.amitdas.site/Chatbot/api.php/?text=' . urlencode($message);
        $simple_response = @file_get_contents($simple_api_url);
        $simple_response = trim($simple_response);

        echo $simple_response ?: "Hi there! ðŸ‘‹ I'm your assistant from TextSnap. How can I help you today?";
        exit;
    }
}

// âœ… 3. TextSnap Knowledge Base
$textsnap_knowledge = [
    'what is textsnap' => "TextSnap is an online platform that allows users to create, save, and share code snippets or plain text securely and easily. It's perfect for developers, writers, and anyone who needs to store or share text quickly.",
    'how does textsnap work' => "You simply paste your text or code, choose optional settings like expiration time or visibility, and generate a link to share. No signup is required for basic usage.",
    'is textsnap free' => "Yes! TextSnap is completely free to use for storing and sharing public and unlisted text snippets.",
    'is login required' => "No login is required to use basic features of TextSnap. Just paste your content and share it.",
    'can i use it for code' => "Absolutely! TextSnap supports syntax highlighting for many programming languages, making it ideal for sharing code.",
    'default' => "I can help you with information about TextSnap. You can ask about features, how it works, or how to share text/code. For more, visit https://textsnap.in"
];

// âœ… 4. Try local match
$best_response = $textsnap_knowledge['default'];
$best_match_score = 0;
$found_match = false;

foreach ($textsnap_knowledge as $key => $response) {
    if ($key === 'default') continue;

    if ($message_lower === $key) {
        $best_response = $response;
        $found_match = true;
        break;
    }

    if (strpos($message_lower, $key) !== false) {
        $match_score = strlen($key);
        if ($match_score > $best_match_score) {
            $best_match_score = $match_score;
            $best_response = $response;
            $found_match = true;
        }
    }

    $key_words = explode(' ', $key);
    $match_count = 0;
    foreach ($key_words as $word) {
        if (strlen($word) > 3 && strpos($message_lower, $word) !== false) {
            $match_count++;
        }
    }

    if ($match_count > 0 && count($key_words) > 0) {
        $score = ($match_count / count($key_words)) * strlen($key);
        if ($score > $best_match_score) {
            $best_match_score = $score;
            $best_response = $response;
            $found_match = true;
        }
    }
}

if ($found_match) {
    echo $best_response;
    exit;
}

// âœ… 5. Fallback to Chatbot API with site-specific context
$api_query = 'check "https://textsnap.in" and tell me ' . $message;
$api_url = 'https://api.amitdas.site/Chatbot/api.php/?text=' . urlencode($api_query);
$api_response = @file_get_contents($api_url);
$api_response = trim($api_response);

// âœ… 6. Final fallback
echo $api_response ?: $textsnap_knowledge['default'];
?>
