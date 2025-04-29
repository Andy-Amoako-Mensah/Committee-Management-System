<?php
$host = "localhost";
$user = "root";
$pass = "Billiondollar1$";
$dbname = "committeemanagementsystem";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $meetingID = $_POST["meetingID"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $location = $_POST["location"];
    $agenda = $_POST["agenda"];

    // Update the meeting details
    $stmt = $conn->prepare("UPDATE meetings SET date = ?, time = ?, location = ?, agenda = ? WHERE meetingID = ?");
    $stmt->bind_param("ssssi", $date, $time, $location, $agenda, $meetingID);

    if ($stmt->execute()) {
        // Redirect to meetings.php after successful update
        // Make sure to flush the headers before redirecting
        header("Location: meetings.html");
        exit();
    } else {
        echo "Error updating meeting: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
