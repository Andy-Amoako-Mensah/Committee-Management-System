<?php
$host = "localhost";
$user = "root";
$pass = "Billiondollar1$";
$dbname = "committeemanagementsystem";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $meetingID = $_POST["meetingID"];
  
  // Delete meeting
  $stmt = $conn->prepare("DELETE FROM meetings WHERE meetingID = ?");
  $stmt->bind_param("i", $meetingID);

  if ($stmt->execute()) {
    echo "Meeting deleted successfully!";
  } else {
    echo "Error deleting meeting: " . $stmt->error;
  }

  $stmt->close();
} else {
  echo "Invalid request.";
}

$conn->close();
?>
