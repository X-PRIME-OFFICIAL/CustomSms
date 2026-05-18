<?php

// Example:
// https://your-vercel-app.vercel.app/api/sms.php?number=017XXXXXXXX&message=Hello

header("Content-Type: application/json");

// ─── ADMIN INFO ───────────────────────
$ADMIN_NAME = "Ahmed Farhan";
$ADMIN_TELEGRAM = "@X_P4IM";
$ADMIN_WHATSAPP = "https://wa.me/18077816349";

// ─── CONFIG ───────────────────────────
$SMS_API_URL = "https://anoncify.xyz/api/sms";
$SMS_API_KEY = "ancfy_TkkjfsnBWgoMrjg7GgBJLIVhjyVuo7";

// ─── GET PARAMETERS ───────────────────
$number = $_GET['number'] ?? '';
$message = $_GET['message'] ?? '';

// ─── VALIDATION ───────────────────────
if (empty($number) || empty($message)) {
    echo json_encode([
        "status" => false,
        "message" => "Missing parameter. Use ?number=017XXXXXXXX&message=Hello",
        "admin" => [
            "name" => $ADMIN_NAME,
            "telegram" => $ADMIN_TELEGRAM,
            "whatsapp" => $ADMIN_WHATSAPP
        ]
    ]);
    exit;
}

if (!preg_match('/^01[0-9]{9}$/', $number)) {
    echo json_encode([
        "status" => false,
        "message" => "Invalid Bangladeshi number",
        "admin" => [
            "name" => $ADMIN_NAME,
            "telegram" => $ADMIN_TELEGRAM,
            "whatsapp" => $ADMIN_WHATSAPP
        ]
    ]);
    exit;
}

// ─── SEND SMS ─────────────────────────
$url = $SMS_API_URL .
    "?key=" . urlencode($SMS_API_KEY) .
    "&number=" . urlencode($number) .
    "&msg=" . urlencode($message);

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 20,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

// ─── RESPONSE ─────────────────────────
if ($error) {
    echo json_encode([
        "status" => false,
        "error" => $error,
        "admin" => [
            "name" => $ADMIN_NAME,
            "telegram" => $ADMIN_TELEGRAM,
            "whatsapp" => $ADMIN_WHATSAPP
        ]
    ]);
    exit;
}

echo json_encode([
    "status" => $httpCode == 200,
    "http_code" => $httpCode,
    "response" => $response,
    "admin" => [
        "name" => $ADMIN_NAME,
        "telegram" => $ADMIN_TELEGRAM,
        "whatsapp" => $ADMIN_WHATSAPP
    ]
], JSON_PRETTY_PRINT);

?>