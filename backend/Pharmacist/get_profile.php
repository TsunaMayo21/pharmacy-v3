<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION['userID'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$userID = $_SESSION['userID'];
$sql = "SELECT userID, name, username, email, phoneNum, role, accountStatus FROM user WHERE userID = '$userID'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    echo json_encode(mysqli_fetch_assoc($result));
}
?>