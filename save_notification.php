<?php
session_start();
header('Content-Type: application/json');

// Database credentials
$host = "localhost";
$user = "root";
$pass = "Billiondollar1$";
$dbname = "committeemanagementsystem";

// Composer autoload (adjust path if needed)
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Connect to DB
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

// Ensure user is logged in
if (!isset($_SESSION['userID'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'User not authenticated']);
    exit;
}

$memberID = $_SESSION['userID'];  // Logged-in user's ID
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';

$response = ['success' => false];

if (!empty($subject) && !empty($message)) {
    // Save notification to DB
    $stmt = $conn->prepare("INSERT INTO notifications (memberID, subject, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $memberID, $subject, $message);

    if ($stmt->execute()) {
        $response['success'] = true;

        // Fetch emails from committee_members
        $result = $conn->query("SELECT email FROM committee_members WHERE email IS NOT NULL");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $mail = new PHPMailer(true);
                try {
                    // SMTP settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'andyamoako07@gmail.com'; // Your Gmail
                    $mail->Password = 'mmhg lczu fsav mflt'; // Use Gmail App Password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Email content
                    $mail->setFrom('andyamoako07@gmail.com', 'Committee System');
                    $mail->addAddress($row['email']);
                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body = nl2br($message); // convert line breaks

                    $mail->send();
                } catch (Exception $e) {
                    $response['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }
        }
    } else {
        $response['error'] = 'Failed to save notification.';
    }
}

echo json_encode($response);
$conn->close();
