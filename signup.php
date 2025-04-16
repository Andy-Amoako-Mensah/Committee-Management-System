<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST['Fname'], $_POST['Lname'], $_POST['email'], $_POST['role'], 
              $_POST['department'], $_POST['psw'], $_POST['psw-repeat'], 
              $_POST['username'], $_POST['committee'])
    ) {
        $Fname = trim($_POST['Fname']);
        $Lname = trim($_POST['Lname']);
        $email = trim($_POST['email']);
        $role = $_POST['role'];
        $department = $_POST['department'];
        $psw = $_POST['psw'];
        $psw_repeat = $_POST['psw-repeat'];
        $username = trim($_POST['username']);
        $committee = $_POST['committee'];

        if ($psw !== $psw_repeat) {
            echo "❌ Passwords do not match.";
            exit;
        }

        $hashed_password = password_hash($psw, PASSWORD_DEFAULT);

        $conn = new mysqli("localhost", "root", "Billiondollar1$", "committeemanagementsystem");

        if ($conn->connect_error) {
            die("❌ Connection failed: " . $conn->connect_error);
        }

        // Check if email exists
        $checkEmailQuery = "SELECT COUNT(*) FROM committee_members WHERE email = ?";
        if ($stmt = $conn->prepare($checkEmailQuery)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($emailCount);
            $stmt->fetch();
            $stmt->close();

            if ($emailCount > 0) {
                echo "❌ This email is already registered.";
                $conn->close();
                exit;
            }
        }

        // Check if username exists
        $checkUsernameQuery = "SELECT COUNT(*) FROM committee_members WHERE username = ?";
        if ($stmt = $conn->prepare($checkUsernameQuery)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($usernameCount);
            $stmt->fetch();
            $stmt->close();

            if ($usernameCount > 0) {
                echo "❌ This username is already taken.";
                $conn->close();
                exit;
            }
        }

        // Insert new member
        $insertQuery = "INSERT INTO committee_members (Fname, Lname, email, username, password, role, affiliation, committee) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($insertQuery)) {
            $stmt->bind_param("ssssssss", $Fname, $Lname, $email, $username, $hashed_password, $role, $department, $committee);
            if ($stmt->execute()) {
                echo "✅ Registration successful!";
            } else {
                echo "❌ Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "❌ Failed to prepare the SQL statement.";
        }

        $conn->close();
    } else {
        echo "❌ Please fill in all required fields.";
    }
}
?>
