<?php
session_start();
include "db_connect.php";

if (isset($_GET['action']) && isset($_GET['id'])) {
    $userId = $_GET['id'];
    $action = $_GET['action'];

    // Use single quotes for $userId because IDs like 'U001' are strings
    if ($action == 'approve') {
        // Pending becomes Pharmacist and is automatically activated
        $query = "UPDATE user SET role = 'PHARMACIST', accountStatus = 'ACTIVE' WHERE userID = '$userId'";
    } 
    elseif ($action == 'promote') {
        // Pharmacist becomes Manager
        $query = "UPDATE user SET role = 'MANAGER' WHERE userID = '$userId'";
    } 
    elseif ($action == 'demote') {
        // Manager goes back to Pharmacist
        $query = "UPDATE user SET role = 'PHARMACIST' WHERE userID = '$userId'";
    } 
    elseif ($action == 'toggle') {
        // Flip between ACTIVE and INACTIVE strings
        $query = "UPDATE user SET accountStatus = 
                  CASE 
                    WHEN accountStatus = 'ACTIVE' THEN 'INACTIVE' 
                    ELSE 'ACTIVE' 
                  END 
                  WHERE userID = '$userId'";
    } 
    elseif ($action == 'delete') {
        $query = "DELETE FROM user WHERE userID = '$userId'";
    }

    // Execute query and redirect
    if (mysqli_query($conn, $query)) {
        header("Location: ../../frontend/Manager/account-management.php?status=updated");
        exit();
    } else {
        echo "Database Error: " . mysqli_error($conn);
    }
} else {
    header("Location: ../../frontend/Manager/account-management.php");
    exit();
}
?>