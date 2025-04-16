<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pass = "Billiondollar1$";
$dbname = "committeemanagementsystem";

// Connect to the database
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

header('Content-Type: application/json');

// Fetch data from meetings table
$sql = "SELECT meetingID, date, time, location, agenda FROM meetings";
$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => "SQL error: " . $conn->error]);
    exit;
}

$meetings = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $meetings[] = $row;
    }
}

echo json_encode($meetings);

$conn->close();
?>
