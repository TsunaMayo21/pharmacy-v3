<?php
include "db_connect.php";

$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($conn, $_GET['keyword']) : '';

// 1. UPDATED SQL: Added s.supplierID to the SELECT list
$sql = "SELECT p.productName, p.brandName, pi.batchNumber, 
               pi.quantity, pi.expiryDate, s.supplierName, s.supplierID 
        FROM product p
        JOIN productitem pi ON p.productID = pi.productID
        JOIN supplier s ON p.supplierID = s.supplierID
        WHERE p.productName LIKE '%$keyword%' 
           OR p.brandName LIKE '%$keyword%' 
           OR pi.batchNumber LIKE '%$keyword%'
        ORDER BY pi.expiryDate ASC";

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $expiry = strtotime($row['expiryDate']);
    $today = time();
    $threeMonths = strtotime('+3 months');

    if ($expiry < $today) {
        $status = '<span class="badge bg-danger">Expired</span>';
    } elseif ($expiry <= $threeMonths) {
        $status = '<span class="badge bg-warning text-dark">Expiring Soon</span>';
    } else {
        $status = '<span class="badge bg-success">Normal</span>';
    }

    // 2. UPDATED HTML: Added the <a> tag for the supplier name
    echo "<tr>
            <td><strong>{$row['productName']}</strong></td>
            <td>{$row['brandName']}</td>
            <td><code>{$row['batchNumber']}</code></td>
            <td>{$row['quantity']}</td>
            <td>
                <a href='supplier-details.php?id={$row['supplierID']}' class='text-decoration-none fw-bold text-primary'>
                    {$row['supplierName']}
                </a>
            </td>
            <td>" . date('d M Y', $expiry) . "</td>
            <td>$status</td>
          </tr>";
}
?>