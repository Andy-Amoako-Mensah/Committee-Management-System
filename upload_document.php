<?php
$host = "localhost";
$user = "root";
$pass = "Billiondollar1$";
$dbname = "committeemanagementsystem";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["file"])) {
    $fileName = basename($_FILES["file"]["name"]);
    $targetPath = $uploadDir . $fileName;
    $description = $_POST["description"] ?? "";

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetPath)) {
        $stmt = $conn->prepare("INSERT INTO documents (name, filePath, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $fileName, $targetPath, $description);
        if ($stmt->execute()) {
            echo "File uploaded and saved successfully.";
        } else {
            echo "Failed to save file info to database.";
        }
    } else {
        echo "Failed to upload file.";
    }
}
?>
