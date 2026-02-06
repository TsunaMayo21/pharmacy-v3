<?php
include "db_connect.php";

// 1. Total Products count
$totalProducts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM product"))['t'];

// 2. Expiring Soon (Next 30 days)
$expiringSoon = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM productitem WHERE expiryDate BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)"))['t'];

// 3. Expired Items (Date is in the past)
$expiredItems = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM productitem WHERE expiryDate < CURDATE()"))['t'];

// 4. Pending Approvals (Users with 'Pending' status)
$pendingUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM user WHERE role = 'PENDING'"))['t'];

header('Content-Type: application/json');
echo json_encode([
    'totalProducts' => $totalProducts,
    'expiringSoon'  => $expiringSoon,
    'expiredItems'  => $expiredItems,
    'pendingUsers'  => $pendingUsers
]);