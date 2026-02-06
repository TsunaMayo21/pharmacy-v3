<?php
include "db_connect.php";
$q = $_GET['q'];
$sql = "SELECT * FROM supplier WHERE supplierName LIKE '%$q%' LIMIT 5";
$result = mysqli_query($conn, $sql);
$suppliers = [];
while($row = mysqli_fetch_assoc($result)) {
    $suppliers[] = $row;
}
echo json_encode($suppliers);
?>