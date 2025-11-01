
<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $message = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '';

    // Log form data (for debugging)
    error_log("Form Data: Name=$name, Email=$email, Message=$message");

    // Here you can add logic to save to a database or send an email
    echo json_encode(['success' => true, 'message' => 'Form submitted successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);


}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');
    $position = htmlspecialchars($_POST['position'] ?? '');
    $coverLetter = htmlspecialchars($_POST['coverLetter'] ?? '');

    error_log("Form Data: Name=$name, Email=$email, Message=$message, Position=$position, CoverLetter=$coverLetter");
    echo json_encode(['success' => true, 'message' => 'Application submitted successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}



?>

