<?php
session_start();
include "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_SESSION['userID'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    $phone = mysqli_real_escape_string($conn, $_POST['phoneNum']);

    // Check if new username is taken by someone else
    $check = "SELECT userID FROM user WHERE username='$username' AND userID != '$userID'";
    if (mysqli_num_rows(mysqli_query($conn, $check)) > 0) {
        echo "<script>alert('Username already taken!'); window.history.back();</script>";
        exit();
    }

    if (!empty($pass)) {
    // This creates a 60-character string
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
    }

    $sql = "UPDATE user SET name='$name', username='$username', email='$email', password='$hashed_pass' , phoneNum='$phone' WHERE userID='$userID'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='../../frontend/Manager/account-profile.html';</script>";
    } else {
        echo "<script>alert('Update failed.'); window.history.back();</script>";
    }
}
?>