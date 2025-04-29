<?php
$host = "localhost";
$user = "root";
$pass = "Billiondollar1$";
$dbname = "committeemanagementsystem";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch document details including the description
$result = $conn->query("SELECT documentID, name, filePath, uploadTime, description FROM documents ORDER BY uploadTime DESC");
$documents = [];

while ($row = $result->fetch_assoc()) {
    $documents[] = $row;
}

// Output as JSON
echo json_encode($documents);
?>
