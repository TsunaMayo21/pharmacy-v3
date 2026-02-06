<?php
$sname = "localhost";
$uname = "root";
$password = "1234";
$db_name = "pharmacy v2"; // Replace with your actual database name

$conn = mysqli_connect($sname, $uname, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>