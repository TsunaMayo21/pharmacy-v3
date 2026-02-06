<?php

session_start(); 
include "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = mysqli_real_escape_string($conn, $_POST['user_id']);
    $pass = $_POST['password'];

    $sql = "SELECT * FROM user WHERE username='$user_input' OR email='$user_input' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($pass, $row['password'])) {
            
            // 1. Check if account is ACTIVE
            if (strtoupper($row['accountStatus']) === 'ACTIVE') {
                
                $_SESSION['userID'] = $row['userID'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = strtoupper($row['role']); // Ensure role is uppercase for logic

                // 2. Role-Based Directory Redirection
                if ($_SESSION['role'] === 'MANAGER') {
                    header("Location: ../frontend/Manager/dashboard.html");
                } 
                elseif ($_SESSION['role'] === 'PHARMACIST') {
                    header("Location: ../frontend/Pharmacist/dashboard.html");
                } 
                else {
                    // Fallback for roles that aren't defined yet
                    header("Location: ../frontend/login.html");
                }
                exit();

            } else {
                echo "<script>alert('Account pending approval. Please contact your Manager.'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Incorrect password.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('User not found.'); window.history.back();</script>";
    }
}

?>

