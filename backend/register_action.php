<?php
include "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];
    $comfirm_pass = $_POST['comfirm_password']; // Get the confirm password field

    // 1. Validate that passwords match
    if ($pass !== $comfirm_pass) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit();
    }

    // 2. Check for existing Username or Email (Unique validation) [cite: 25]
    $check_query = "SELECT * FROM user WHERE username='$username' OR email='$email' LIMIT 1";
    $result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Username or Email already exists!'); window.history.back();</script>";
        exit();
    }

    // 3. Generate Auto-ID (e.g., U001) [cite: 27]
    $res = mysqli_query($conn, "SELECT userID FROM user ORDER BY userID DESC LIMIT 1");
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $number = (int)substr($row['userID'], 1) + 1;
        $newUserID = "U" . str_pad($number, 3, "0", STR_PAD_LEFT);
    } else {
        $newUserID = "U001";
    }

    // 4. Hash password for security [cite: 35]
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    // 5. Insert with role=PENDING and status=INACTIVE [cite: 29, 30]
    $sql = "INSERT INTO user (userID, username, password, name, email, role, accountStatus) 
            VALUES ('$newUserID', '$username', '$hashed_pass', '$name', '$email', 'PENDING', 'INACTIVE')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Registration success! Waiting for Manager approval.'); window.location.href='../frontend/login.html';</script>";
    } else {
        echo "<script>alert('Registration failed. Please try again.'); window.history.back();</script>";
    }
}
?>