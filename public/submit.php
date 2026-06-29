<?php
header('Content-Type: application/json');

// শুধু JSON POST ডাটা গ্রহণ করবে (Node.js থেকে)
// FormData এবং JSON দুটোই handle করবে

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (strpos($contentType, 'application/json') !== false) {
    $data = json_decode(file_get_contents('php://input'), true);
} else {
    $data = $_POST;
}



if (!$data || !isset($data['name']) || !isset($data['email'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

// ডাটা স্যানিটাইজ
$name    = htmlspecialchars(trim($data['name']));
$email   = filter_var(trim($data['email']), FILTER_VALIDATE_EMAIL);
$message = htmlspecialchars(trim($data['message'] ?? ''));
$position = htmlspecialchars(trim($data['position'] ?? ''));
$coverLetter = htmlspecialchars(trim($data['coverLetter'] ?? ''));

// ইমেল ভ্যালিডেশন
if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Invalid email']);
    exit;
}


// reCAPTCHA Verification
$recaptcha_secret = '6LckDjotAAAAKnKCUVBPEkJUDcGOoyEYtzdZT7W';
$recaptcha_response = $data['g-recaptcha-response'] ?? $_POST['g-recaptcha-response'] ?? '';
if (!empty($recaptcha_response)) {
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
    $captcha_result = json_decode($verify, true);
    if (!$captcha_result['success']) {
        echo json_encode(['success' => false, 'message' => 'reCAPTCHA verification failed!']);
        exit;
    }
}

// ডাটাবেস কানেকশন
require_once 'db.php';

try {
    // Contact Form
    if (!empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $message]);
    }
    // Job Application
    elseif (!empty($position)) {
        $stmt = $pdo->prepare("INSERT INTO applications (name, email, position, cover_letter) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $position, $coverLetter]);
    }

    echo json_encode(['success' => true, 'message' => 'Submitted successfully!']);
    
} catch (Exception $e) {
    error_log("DB Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>
