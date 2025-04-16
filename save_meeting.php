<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pass = "Billiondollar1$";
$dbname = "committeemanagementsystem";

// Connect
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
  exit;
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $meetingID = isset($_POST['meetingID']) ? $_POST['meetingID'] : null;
  $date = $_POST['date'];
  $time = $_POST['time'];
  $location = $_POST['location'];
  $agenda = $_POST['agenda'];

  if ($meetingID) {
    // Update existing meeting
    $sql = "UPDATE meetings SET date = ?, time = ?, location = ?, agenda = ? WHERE meetingID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $date, $time, $location, $agenda, $meetingID);
    if ($stmt->execute()) {
      // Redirect to meetings page after successful update
      header('Location: meetings.html');
      exit;
    }
  } else {
    // Insert new meeting
    $sql = "INSERT INTO meetings (date, time, location, agenda) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $date, $time, $location, $agenda);
    if ($stmt->execute()) {
      // Redirect to meetings page after successful insertion
      header('Location: meetings.html');
      exit;
    }
  }
}

// Close connection
$conn->close();
?>
