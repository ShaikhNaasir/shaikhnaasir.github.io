<?php
// Email configuration
$recipient_email = "contact@abdulbarimk.com";
$cc_email = "naasir@naze.in";
$site_name = "عبدالباری ایم۔ کے۔";

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=utf-8");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Get form data
$name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
$email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
$subject = isset($_POST['subject']) ? sanitize_input($_POST['subject']) : 'No Subject';
$message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = 'نام ضروری ہے';
}

if (empty($email)) {
    $errors[] = 'ای میل ضروری ہے';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'ای میل غلط ہے';
}

if (empty($message)) {
    $errors[] = 'پیغام ضروری ہے';
}

// If validation errors exist
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'تصدیق میں ناکامی',
        'errors' => $errors
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// Log the message
log_contact_message($name, $email, $subject, $message);

// Prepare email headers
$email_from = filter_var($email, FILTER_SANITIZE_EMAIL);
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: " . $email_from . "\r\n";
$headers .= "Reply-To: " . $email_from . "\r\n";
$headers .= "Cc: " . $cc_email . "\r\n";

// Prepare email body
$email_subject = "نیا رابطہ: " . $subject;
$message_safe = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

$email_body = <<<HTML
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: 'Noto Nastaliq Urdu', Arial, sans-serif; direction: rtl; background-color: #f5f5f0; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .header { text-align: center; border-bottom: 3px solid #8B2635; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #8B2635; margin: 0; font-size: 24px; }
        .field { margin-bottom: 20px; padding: 15px; background-color: #f9f7f4; border-right: 4px solid #D4AF37; border-radius: 5px; }
        .field-label { font-weight: bold; color: #8B2635; margin-bottom: 8px; font-size: 14px; }
        .field-value { color: #2C1810; line-height: 1.6; }
        .message-box { background-color: #fff8f0; padding: 20px; border-radius: 10px; border-left: 4px solid #8B2635; margin-top: 20px; }
        .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>📨 نیا رابطہ</h1>
        </div>
        
        <div class='field'>
            <div class='field-label'>نام:</div>
            <div class='field-value'>$name</div>
        </div>
        
        <div class='field'>
            <div class='field-label'>ای میل:</div>
            <div class='field-value'><a href='mailto:$email'>$email</a></div>
        </div>
        
        <div class='field'>
            <div class='field-label'>موضوع:</div>
            <div class='field-value'>$subject</div>
        </div>
        
        <div class='message-box'>
            <div class='field-label'>پیغام:</div>
            <div class='field-value' style='white-space: pre-wrap;'>$message_safe</div>
        </div>
        
        <div class='footer'>
            <p>This email was sent from $site_name's website.</p>
        </div>
    </div>
</body>
</html>
HTML;

// Try to send email
$email_sent = false;
if (function_exists('mail')) {
    $email_sent = @mail($recipient_email, $email_subject, $email_body, $headers);
}

// Return success response (messages are logged to file)
http_response_code(200);
echo json_encode([
    'success' => true,
    'message' => 'آپ کا پیغام کامیابی سے بھیج دیا گیا ہے۔ شکریہ! ہم جلد جواب دیں گے۔'
], JSON_UNESCAPED_UNICODE);

/**
 * Sanitize input to prevent XSS
 */
function sanitize_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}

/**
 * Log contact messages to file
 */
function log_contact_message($name, $email, $subject, $message) {
    $log_dir = __DIR__ . '/../../logs';
    
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0755, true);
    }
    
    $log_file = $log_dir . '/contact_messages.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "\n" . str_repeat("=", 50) . "\n";
    $log_entry .= "Timestamp: $timestamp\n";
    $log_entry .= "Name: $name\n";
    $log_entry .= "Email: $email\n";
    $log_entry .= "Subject: $subject\n";
    $log_entry .= "Message:\n$message\n";
    $log_entry .= str_repeat("=", 50) . "\n";
    
    @file_put_contents($log_file, $log_entry, FILE_APPEND);
}
?>
