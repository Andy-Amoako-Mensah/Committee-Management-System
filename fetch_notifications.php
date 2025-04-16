<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "Billiondollar1$";
$dbname = "committeemanagementsystem";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

// Fetch notifications
$sql = "SELECT notificationID, memberID, subject, message, dateCreated FROM notifications ORDER BY dateCreated DESC";
$result = $conn->query($sql);

$notifications = [];

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
  }
}

echo json_encode($notifications);

$conn->close();
?>
