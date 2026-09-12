<?php
include "db_connect.php";

$type = $_GET['type'] ?? '';
$startDate = $_GET['startDate'] ?? '';
$endDate = $_GET['endDate'] ?? '';

// Helper function to build SQL Date Filter
function buildDateFilter($column, $start, $end) {
    if (!empty($start) && !empty($end)) {
        return " AND $column BETWEEN '$start' AND '$end'";
    } elseif (!empty($start)) {
        return " AND $column >= '$start'";
    } elseif (!empty($end)) {
        return " AND $column <= '$end'";
    }
    return "";
}

// 1. Stock Report
if ($type === 'stock') {
    $filter = buildDateFilter("pi.expiryDate", $startDate, $endDate);
    $query = "SELECT p.productName, pi.batchNumber, pi.quantity, pi.expiryDate 
              FROM product p 
              JOIN productitem pi ON p.productID = pi.productID
              WHERE 1=1 $filter";
    
    $result = mysqli_query($conn, $query);
    echo "<thead><tr><th>Product</th><th>Batch</th><th>Quantity</th><th>Expiry</th></tr></thead><tbody>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>{$row['productName']}</td><td>{$row['batchNumber']}</td><td>{$row['quantity']}</td><td>{$row['expiryDate']}</td></tr>";
    }
    echo "</tbody>";
}

// 2. Expiry & Warning Report (New)
elseif ($type === 'expiry') {
    // Usually filters by products expiring soon (e.g., within 30 days)
    $filter = buildDateFilter("pi.expiryDate", $startDate, $endDate);
    $query = "SELECT p.productName, pi.batchNumber, pi.expiryDate, 
              DATEDIFF(pi.expiryDate, CURDATE()) as days_left
              FROM productitem pi
              JOIN product p ON pi.productID = p.productID
              WHERE pi.expiryDate <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) $filter
              ORDER BY pi.expiryDate ASC";

    $result = mysqli_query($conn, $query);
    echo "<thead><tr><th>Product</th><th>Batch</th><th>Expiry Date</th><th>Status</th></tr></thead><tbody>";
    while ($row = mysqli_fetch_assoc($result)) {
        $status = ($row['days_left'] < 0) ? "EXPIRED" : $row['days_left'] . " days left";
        echo "<tr><td>{$row['productName']}</td><td>{$row['batchNumber']}</td><td>{$row['expiryDate']}</td><td>$status</td></tr>";
    }
    echo "</tbody>";
}

// 3. Disposal Report
elseif ($type === 'disposal') {
    $filter = buildDateFilter("d.disposalDate", $startDate, $endDate);
    $query = "SELECT d.disposalDate, p.productName, d.batchNumber, d.method, d.disposalStatus 
              FROM disposalrecord d
              JOIN productitem pi ON d.batchNumber = pi.batchNumber
              JOIN product p ON pi.productID = p.productID
              WHERE 1=1 $filter
              ORDER BY d.disposalDate DESC";
    
    $result = mysqli_query($conn, $query);
    echo "<thead><tr><th>Date</th><th>Product</th><th>Batch</th><th>Method</th><th>Status</th></tr></thead><tbody>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>{$row['disposalDate']}</td><td>{$row['productName']}</td><td>{$row['batchNumber']}</td><td>{$row['method']}</td><td>{$row['disposalStatus']}</td></tr>";
    }
    echo "</tbody>";
}

// 4. Supplier Inventory Report (New)
elseif ($type === 'supplier') {
    $query = "SELECT s.supplierName, p.productName, pi.batchNumber, pi.quantity 
              FROM supplier s
              JOIN product p ON s.supplierID = p.supplierID
              JOIN productitem pi ON p.productID = pi.productID
              ORDER BY s.supplierName ASC";

    $result = mysqli_query($conn, $query);
    echo "<thead><tr><th>Supplier</th><th>Product</th><th>Batch</th><th>Stock Level</th></tr></thead><tbody>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>{$row['supplierName']}</td><td>{$row['productName']}</td><td>{$row['batchNumber']}</td><td>{$row['quantity']}</td></tr>";
    }
    echo "</tbody>";
}
?>
