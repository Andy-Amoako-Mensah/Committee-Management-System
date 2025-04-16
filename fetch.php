<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pass = "Billiondollar1$"; // set your DB password if any
$dbname = "committeemanagementsystem";

// Connect
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
  exit;
}

// Fetch data
$sql = "SELECT userID, CONCAT(Fname, ' ', Lname) AS Name, role, email, datejoined FROM committee_members";
$result = $conn->query($sql);

$members = [];

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $members[] = $row;
  }
}

header('Content-Type: application/json');
echo json_encode($members);

$conn->close();
?>
