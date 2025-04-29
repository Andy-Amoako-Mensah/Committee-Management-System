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
  $userID = $_POST["userID"];
  $Fname = $_POST["Fname"];
  $Lname = $_POST["Lname"];
  $email = $_POST["email"];
  $role = $_POST["role"];
  $datejoined = $_POST["datejoined"];
  $password = $_POST["password"]; // this might be empty

  if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE committee_members SET Fname = ?, Lname = ?, email = ?, role = ?, datejoined = ?, password = ? WHERE userID = ?");
    $stmt->bind_param("ssssssi", $Fname, $Lname, $email, $role, $datejoined, $hashedPassword, $userID);
  } else {
    $stmt = $conn->prepare("UPDATE committee_members SET Fname = ?, Lname = ?, email = ?, role = ?, datejoined = ? WHERE userID = ?");
    $stmt->bind_param("sssssi", $Fname, $Lname, $email, $role, $datejoined, $userID);
  }

  if ($stmt->execute()) {
    echo "<script>alert('User updated successfully'); window.location.href='dashboard.html';</script>";
  } else {
    echo "<script>alert('Error updating user'); window.history.back();</script>";
  }

  $stmt->close();
} else {
  echo "Invalid request.";
}

$conn->close();
?>
