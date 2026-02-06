<?php
session_start();
include "db_connect.php";

if (isset($_POST['submit_disposal'])) {
    $dispID  = mysqli_real_escape_string($conn, $_POST['disposal_ID']);
    $batch   = mysqli_real_escape_string($conn, $_POST['batchNumber']);
    $date    = mysqli_real_escape_string($conn, $_POST['disposalDate']);
    $method  = mysqli_real_escape_string($conn, $_POST['method']);
    $user    = mysqli_real_escape_string($conn, $_POST['managedBy']); // Captured from hidden input
    $status  = "COMPLETED";

    // UPDATED QUERY: Include managedBy
    $sql = "INSERT INTO disposalrecord (disposal_ID, batchNumber, disposalDate, method, disposalStatus, managedBy) 
            VALUES ('$dispID', '$batch', '$date', '$method', '$status', '$user')";

    if (mysqli_query($conn, $sql)) {
        // Update stock
        mysqli_query($conn, "UPDATE productitem SET quantity = 0 WHERE batchNumber = '$batch'");
        
        header("Location: ../frontend/Manager/product-list.php?msg=disposed");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>