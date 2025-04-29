<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "Billiondollar1$";
$dbname = "committeemanagementsystem";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  echo json_encode(["success" => false, "error" => "DB connection failed"]);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["userIDs"]) || !is_array($data["userIDs"])) {
  echo json_encode(["success" => false, "error" => "Invalid data"]);
  exit;
}

$userIDs = $data["userIDs"];
$placeholders = implode(',', array_fill(0, count($userIDs), '?'));
$types = str_repeat('i', count($userIDs));

$stmt = $conn->prepare("DELETE FROM committee_members WHERE userID IN ($placeholders)");
$stmt->bind_param($types, ...$userIDs);

if ($stmt->execute()) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
