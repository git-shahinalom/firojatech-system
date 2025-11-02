<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($data) {
        // Gmail SMTP দিয়ে ইমেল (PHPMailer ব্যবহার করো)
        // প্রথমে PHPMailer ইনস্টল: composer require phpmailer/phpmailer
        
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;

        $mail = new PHPMailer(true);

        try {
            // সার্ভার সেটিং
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'muhammadshahinalom43@gmail.com';  // তোমার Gmail
            $mail->Password = 'nmip lczc imfp jljs';     // Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // ইমেল সেটিং
            $mail->setFrom('muhammadshahinalom43@gmail.com', 'Firojatech');
            $mail->addAddress('admin@firojatech.com', 'Admin');
            $mail->isHTML(true);
            $mail->Subject = 'New Form Submission';

            $mail->Body = "
                <h2>New Contact Form</h2>
                <p><strong>Name:</strong> {$data['name']}</p>
                <p><strong>Email:</strong> {$data['email']}</p>
                <p><strong>Message:</strong> {$data['message']}</p>
                <hr>
                <p>Submitted at: " . date('Y-m-d H:i:s') . "</p>
            ";

            $mail->send();
            echo json_encode(['success' => true, 'message' => 'Email sent!']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
?>