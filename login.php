<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "Billiondollar1$";
$dbname = "committeemanagementsystem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input using the correct form field names
    $username = mysqli_real_escape_string($conn, $_POST['uname']);
    $password = mysqli_real_escape_string($conn, $_POST['psw']);

    // Query to check if the username exists
    $sql = "SELECT * FROM committee_members WHERE username = '$username'";
    $result = $conn->query($sql);

    // Check if user exists
    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect to dashboard
            header("Location: dashboard.html");
            exit();
        } else {
            echo "<p>Invalid username or password.</p>";
        }
    } else {
        echo "<p>Invalid username or password.</p>";
    }
}

$conn->close();
?>
