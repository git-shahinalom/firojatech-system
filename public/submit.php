
<?php
header('Content-Type: application/json');

// শুধু JSON POST ডাটা গ্রহণ করবে (Node.js থেকে)
$data = json_decode(file_get_contents('php://input'), true);

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