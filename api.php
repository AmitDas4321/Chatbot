<?php
header("Content-Type: application/json");

// Function to generate the SHA256 signature
function generate_signature($time, $text, $secret = "") {
    $message = $time . ":" . $text . ":" . $secret;
    return hash("sha256", $message);
}

// Check if "text" parameter is set
if (!isset($_GET["text"]) || empty(trim($_GET["text"]))) {
    echo json_encode(["error" => "Missing 'text' parameter"]);
    exit;
}

$text = trim($_GET["text"]);

// API URL
$url = "https://chat10.free2gpt.xyz/api/generate";

// Headers for the request
$headers = [
    "User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36",
    "Accept: */*",
    "Accept-Language: en-US,en;q=0.9",
    "Accept-Encoding: gzip, deflate, br",
    "Content-Type: text/plain;charset=UTF-8",
    "Referer: https://chat10.free2gpt.xyz/",
    "Origin: https://chat10.free2gpt.xyz",
    "Sec-Fetch-Dest: empty",
    "Sec-Fetch-Mode: cors",
    "Sec-Fetch-Site: same-origin",
    "Sec-Ch-Ua: \"Chromium\";v=\"127\", \"Not)A;Brand\";v=\"99\"",
    "Sec-Ch-Ua-Mobile: ?0",
    "Sec-Ch-Ua-Platform: \"Linux\"",
    "Cache-Control: no-cache",
    "Pragma: no-cache",
    "Priority: u=1, i"
];

// Generate timestamp
$timestamp = round(microtime(true) * 1000);

// Define messages
$system_message = ["role" => "system", "content" => ""];
$messages = [$system_message, ["role" => "user", "content" => $text]];

// Generate the signature
$sign = generate_signature($timestamp, $text);

// Prepare data payload
$data = json_encode([
    "messages" => $messages,
    "time" => $timestamp,
    "pass" => null,
    "sign" => $sign
]);

// Initialize cURL request
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

// Execute request
$response = curl_exec($ch);
curl_close($ch);

// Output response
echo $response;
?>
