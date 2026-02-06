<?php
session_start();
include "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prodName = $_POST['productName'];
    $brandName = $_POST['brandName']; // ADD THIS LINE
    $batchNum = $_POST['batchNumber'];
    $qty = $_POST['quantity'];
    $expiry = $_POST['expiryDate'];
    $supName = $_POST['supplierName'];

    // 1. Logic to handle Supplier (Find or Create)
    $supCheck = mysqli_query($conn, "SELECT supplierID FROM supplier WHERE supplierName = '$supName'");
    if (mysqli_num_rows($supCheck) > 0) {
        $supID = mysqli_fetch_assoc($supCheck)['supplierID'];
    } else {
        // Collect extra supplier info if it's a new supplier
        $supEmail = $_POST['supplierEmail'] ?? '';
        $picName = $_POST['picName'] ?? '';
        $picPhone = $_POST['picPhoneNum'] ?? '';
        
        $insSup = "INSERT INTO supplier (supplierName, supplierEmail, picName, picPhoneNum) 
                   VALUES ('$supName', '$supEmail', '$picName', '$picPhone')";
        mysqli_query($conn, $insSup);
        $supID = mysqli_insert_id($conn);
    }

    // 2. Insert Product (NOW INCLUDING BRAND NAME)
    $insProd = "INSERT INTO product (productName, brandName, supplierID) 
                VALUES ('$prodName', '$brandName', '$supID')";
    mysqli_query($conn, $insProd);
    $prodID = mysqli_insert_id($conn);

    // 3. Insert ProductItem
    $insItem = "INSERT INTO productItem (batchNumber, productID, quantity, expiryDate) 
                VALUES ('$batchNum', '$prodID', '$qty', '$expiry')";
    
    if (mysqli_query($conn, $insItem)) {
        echo "<script>alert('Product Added!'); window.location.href='../frontend/Manager/product-list.php';</script>";
    }
}
?>