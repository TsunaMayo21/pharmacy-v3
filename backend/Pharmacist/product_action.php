<?php
include "db_connect.php";

// 1. Check if the parameters exist FIRST
if (isset($_GET['action'])) {
    
    // 2. Define the variable only if it exists
    $action = $_GET['action'];

    // --- DELETE LOGIC ---
    if ($action == 'delete' && isset($_GET['batch'])) {
        $batch = mysqli_real_escape_string($conn, $_GET['batch']);
        $query = "DELETE FROM productitem WHERE batchNumber = '$batch'";
        
        if (mysqli_query($conn, $query)) {
            header("Location: ../../frontend/Pharmacist/product-list.php?msg=deleted");
            exit();
        }
    } 

    // --- UPDATE LOGIC ---
    elseif ($action == 'update') {
        $old_batch = mysqli_real_escape_string($conn, $_POST['old_batchNumber']);
        $new_batch = mysqli_real_escape_string($conn, $_POST['batchNumber']);
        $pName = mysqli_real_escape_string($conn, $_POST['productName']);
        $qty = mysqli_real_escape_string($conn, $_POST['quantity']);
        $exp = mysqli_real_escape_string($conn, $_POST['expiryDate']);

        // Update queries here...
        mysqli_query($conn, "UPDATE productitem SET batchNumber='$new_batch', quantity='$qty', expiryDate='$exp' WHERE batchNumber='$old_batch'");
        
        header("Location: ../../frontend/Pharmacist/product-list.php?msg=updated");
        exit();
    }

} else {
    // If someone tries to access the file directly without an action
    header("Location: ../../frontend/Pharmacist/product-list.php");
    exit();
}
?>