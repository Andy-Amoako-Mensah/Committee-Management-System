<?php
session_start();

// Database credentials
$host = "localhost";
$user = "root";
$pass = "Billiondollar1$";
$dbname = "committeemanagementsystem";

// Create database connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
if (!isset($_SESSION['userID'])) {
    die("Unauthorized access.");
}

$userID = $_SESSION['userID'];

// Fetch current user data (for GET request)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare("SELECT email FROM committee_members WHERE userID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Return the current email as JSON
        echo json_encode(['email' => $user['email']]);
    } else {
        echo json_encode(['error' => 'User not found']);
    }

    exit();
}

// Process POST request for updating account details (existing code here)
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$new_email = $_POST['new_email'] ?? '';
$confirm_email = $_POST['confirm_email'] ?? '';
$profile_image = $_FILES['profile_image'] ?? null;

// Fetch current user data
$stmt = $conn->prepare("SELECT password, email, profile_image FROM committee_members WHERE userID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Verify current password
if (!password_verify($current_password, $user['password'])) {
    die("Incorrect current password.");
}

// Prepare updates
$updates = [];
$params = [];
$types = "";

// Update password if needed
if (!empty($new_password)) {
    if ($new_password !== $confirm_password) {
        die("New passwords do not match.");
    }
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $updates[] = "password = ?";
    $params[] = $hashed_password;
    $types .= "s";
}

// Update email if needed
if (!empty($new_email)) {
    if ($new_email !== $confirm_email) {
        die("Emails do not match.");
    }
    $updates[] = "email = ?";
    $params[] = $new_email;
    $types .= "s";
}

// Update profile image if needed
if ($profile_image && $profile_image['error'] === 0) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $file_type = $profile_image['type'];

    if (!in_array($file_type, $allowed_types)) {
        die("Invalid image type. Only JPEG, PNG, and GIF are allowed.");
    }

    $file_name = $userID . "_" . basename($profile_image['name']);
    $upload_dir = 'uploads/profile_images/';
    $file_path = $upload_dir . $file_name;

    if (!move_uploaded_file($profile_image['tmp_name'], $file_path)) {
        die("Error uploading the image.");
    }

    // If a profile image is updated, add to the updates
    $updates[] = "profile_image = ?";
    $params[] = $file_path;
    $types .= "s";
}

// Run the update if there are changes
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
