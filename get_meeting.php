<?php
header('Content-Type: application/json');

// Database credentials
$host = "localhost";
$user = "root";
$pass = "Billiondollar1$";
$dbname = "committeemanagementsystem";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "DB connection failed"]);
    exit;
}

// Check if 'id' parameter is passed
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(["success" => false, "error" => "Invalid meeting ID"]);
    exit;
}

$meetingID = $_GET['id'];

// Fetch the meeting record
$stmt = $conn->prepare("SELECT * FROM meetings WHERE meetingID = ?");
$stmt->bind_param("i", $meetingID);
$stmt->execute();
$result = $stmt->get_result();

// Check if meeting exists
if ($result->num_rows > 0) {
    $meeting = $result->fetch_assoc();
    echo json_encode(["success" => true, "meeting" => $meeting]);
} else {
    echo json_encode(["success" => false, "error" => "Meeting not found"]);
}

$stmt->close();
$conn->close();
?>
