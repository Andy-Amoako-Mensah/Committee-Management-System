<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "Billiondollar1$";
$dbname = "committeemanagementsystem";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Make sure user is logged in
if (!isset($_SESSION['userID'])) {
    http_response_code(403);
    die("Unauthorized access.");
}

$userID = $_SESSION['userID'];

// Handle GET request (fetch user info)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare("SELECT email, profile_image FROM committee_members WHERE userID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        echo json_encode([
            'email' => $user['email'],
            'profile_image' => $user['profile_image']
        ]);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
    exit();
}

// Handle profile image upload
if (isset($_POST['upload_image']) && isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
    $profile_image = $_FILES['profile_image'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $file_type = mime_content_type($profile_image['tmp_name']);

    if (!in_array($file_type, $allowed_types)) {
        die("Invalid image type. Only JPEG, PNG, and GIF are allowed.");
    }

    $file_name = $userID . "_" . time() . "_" . basename($profile_image['name']);
    $upload_dir = 'uploads/profile_images/';
    $file_path = $upload_dir . $file_name;

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (!move_uploaded_file($profile_image['tmp_name'], $file_path)) {
        die("Error uploading the image.");
    }

    $stmt = $conn->prepare("UPDATE committee_members SET profile_image = ? WHERE userID = ?");
    $stmt->bind_param("si", $file_path, $userID);
    if ($stmt->execute()) {
        echo "Profile image updated successfully.";
    } else {
        echo "Error updating profile image: " . $conn->error;
    }
    $conn->close();
    exit();
}

// Handle form submission for password/email updates
$current_password = $_POST['current_password'] ?? '';
$new_password     = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$new_email        = $_POST['new_email'] ?? '';
$confirm_email    = $_POST['confirm_email'] ?? '';

$stmt = $conn->prepare("SELECT password, email FROM committee_members WHERE userID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Validate current password before making any changes
if (!empty($current_password) && !password_verify($current_password, $user['password'])) {
    die("Incorrect current password.");
}

$updates = [];
$params = [];
$types = "";

// Handle password update
if (!empty($new_password)) {
    if ($new_password !== $confirm_password) {
        die("New passwords do not match.");
    }
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $updates[] = "password = ?";
    $params[] = $hashed_password;
    $types .= "s";
}

// Handle email update
if (!empty($new_email)) {
    if ($new_email !== $confirm_email) {
        die("Emails do not match.");
    }
    $updates[] = "email = ?";
    $params[] = $new_email;
    $types .= "s";
}

if (!empty($updates)) {
    $sql = "UPDATE committee_members SET " . implode(", ", $updates) . " WHERE userID = ?";
    $params[] = $userID;
    $types .= "i";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo "Account updated successfully.";
    } else {
        echo "Error updating account: " . $conn->error;
    }
} else {
    echo "No changes submitted.";
}

$conn->close();
?>
