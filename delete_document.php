<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "Billiondollar1$";
$dbname = "committeemanagementsystem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the document ID from URL parameters
$documentID = $_GET['id'] ?? '';

if (empty($documentID)) {
    echo "Error: Missing document ID.";
    exit();
}

// Fetch the file path using the document ID
$sql = "SELECT filePath FROM documents WHERE documentID = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $documentID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Error: Document not found in the database.";
    $stmt->close();
    $conn->close();
    exit();
}

$row = $result->fetch_assoc();
$filePath = $row['filePath'];
$stmt->close();

// Attempt to delete the file from the server
if (file_exists($filePath)) {
    if (!unlink($filePath)) {
        echo "Error deleting file from server.";
        $conn->close();
        exit();
    }
} else {
    echo "File does not exist on the server.";
    $conn->close();
    exit();
}

// Delete the record from the database
$deleteSql = "DELETE FROM documents WHERE documentID = ?";
$deleteStmt = $conn->prepare($deleteSql);
$deleteStmt->bind_param("i", $documentID);

if ($deleteStmt->execute()) {
    echo "File deleted successfully.";
} else {
    echo "Error deleting file record from database.";
}

$deleteStmt->close();
$conn->close();
?>
