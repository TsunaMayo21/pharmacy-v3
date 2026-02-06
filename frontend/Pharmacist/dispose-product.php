<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dispose Product</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Existing CSS -->
  <link rel="stylesheet" href="../style.css">

  <style>
    .form-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 40px 48px;
      max-width: 700px;
      width: 100%;
      box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    }

    .warning-box {
      background: #fef2f2;
      border: 1px solid #fecaca;
      border-radius: 12px;
      padding: 16px;
      color: #991b1b;
      margin-bottom: 20px;
    }

    .status-badge {
      display: inline-block;
      padding: 6px 14px;
      border-radius: 20px;
      background: #fef3c7;
      color: #92400e;
      font-weight: 500;
      font-size: 14px;
    }
  </style>
</head>
<body>

<!-- ================= SIDEBAR (UNCHANGED) ================= -->
<div class="sidebar">
  <h3>HH IMS</h3>

<a href="dashboard.html" class="nav-link">Dashboard</a>

<button class="dropdown-btn">Account ▾</button>
<div class="dropdown-container">
  <a href="account-profile.html" class="nav-link">My Profile</a>
</div>

<button class="dropdown-btn">Products ▾</button>
<div class="dropdown-container">
  <a href="add-product.html" class="nav-link">Add Product</a>
  <a href="product-list.php" class="nav-link">Product List</a>
</div>

<button class="dropdown-btn">Reports ▾</button>
<div class="dropdown-container">
  <a href="report-view.html" class="nav-link">View Report</a>
</div>

  <a href="../../backend/logout.php" class="btn btn-danger">Logout</a>
</div>
<!-- ================= END SIDEBAR ================= -->


<!-- ================= CONTENT ================= -->
<div class="content d-flex justify-content-center align-items-start pt-5">

    <?php
    session_start();
    include "../../backend/db_connect.php";

    // 1. Check if user is logged in and define $current_user
    if (isset($_SESSION['userID'])) {
        $current_user = $_SESSION['userID']; 
    } else {
        // Optional: Redirect to login if session is missing
        $current_user = "Guest/Unauthorized"; 
    }

    // 1. Initialize variables with default values to prevent "Undefined" errors
    $batch = isset($_GET['batch']) ? mysqli_real_escape_string($conn, $_GET['batch']) : '';
    $product_name = "Unknown Product"; 

    // 2. Only attempt to fetch if a batch number exists
    if (!empty($batch)) {
        $res = mysqli_query($conn, "SELECT p.productName FROM product p 
                                    JOIN productitem pi ON p.productID = pi.productID 
                                    WHERE pi.batchNumber = '$batch'");
        
        if ($res && mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $product_name = $row['productName'];
        }
    }

    // 3. ID Generation Logic (Manual VARCHAR version)
    $query = "SELECT disposal_ID FROM disposalrecord ORDER BY disposal_ID DESC LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $lastID = $row['disposal_ID'];
        $number = (int)substr($lastID, 5); 
        $newNumber = $number + 1;
        $displayID = "DISP-" . str_pad($newNumber, 3, "0", STR_PAD_LEFT);
    } else {
        $displayID = "DISP-001";
    }
    ?>

    <div class="form-card">
        <h3 class="fw-bold mb-1">Dispose Product</h3>
        <p class="text-muted mb-4">Product: <strong><?php echo $product_name; ?></strong> (Batch: <?php echo $batch; ?>)</p>

        <form action="../../backend/Pharmacist/process_disposal.php" method="POST">
            <input type="hidden" name="batchNumber" value="<?php echo $batch; ?>">

            <div class="warning-box">
                <strong>Warning:</strong> This will record this batch as disposed and set status to COMPLETED.
            </div>

            <div class="mb-3">
                <label class="form-label">Disposal ID</label>
                <input type="text" name="disposal_ID" class="form-control" value="<?php echo $displayID; ?>" readonly style="background-color: #e9ecef;">
            </div>

            <div class="mb-3">
                <label class="form-label">Disposal Date</label>
                <input type="date" 
                      name="disposalDate" 
                      id="disposalDate" 
                      class="form-control" 
                      required 
                      value="<?php echo date('Y-m-d'); ?>" 
                      min="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="mb-4">
                <label class="form-label">Disposal Method</label>
                <select name="method" class="form-select" required>
                    <option value="">Select disposal method</option>
                    <option value="Return to Supplier">Return to Supplier</option>
                    <option value="Disposed according to SOP">Disposed according to SOP</option>
                </select>
            </div>
            <p class="text-muted">Managed By: <strong><?php echo $current_user; ?></strong></p>

            <input type="hidden" name="managedBy" value="<?php echo $current_user; ?>">

            <div class="d-flex justify-content-between">
                <a href="product-list.php" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" name="submit_disposal" class="btn btn-danger">Confirm Disposal</button>
            </div>
        </form>
    </div>

</div>
<!-- ================= END CONTENT ================= -->


<script>
function confirmDispose() {
  const confirmAction = confirm("Are you sure you want to dispose this product?");
  if (confirmAction) {
    alert("Product disposed successfully (frontend only).");
    window.location.href = "product-list.php";
  }
}

document.querySelectorAll(".dropdown-btn").forEach(btn => {
  btn.onclick = () => {
    const menu = btn.nextElementSibling;
    menu.style.display =
      menu.style.display === "block" ? "none" : "block";
  };
});
</script>

<script>
  // get current file name only
  const currentPage = window.location.pathname.split("/").pop();

  // remove ALL active classes (important)
  document.querySelectorAll(".sidebar .active").forEach(el => {
    el.classList.remove("active");
  });

  // find exact matching link
  document.querySelectorAll(".sidebar a.nav-link").forEach(link => {
    const linkPage = link.getAttribute("href");

    if (linkPage === currentPage) {
      link.classList.add("active");

      // open its dropdown if exists
      const dropdown = link.closest(".dropdown-container");
      if (dropdown) {
        dropdown.style.display = "block";
      }
    }
  });

  document.getElementById('disposalDate').addEventListener('change', function() {
    const selectedDate = new Date(this.value);
    const today = new Date();
    // Reset time to midnight for accurate date comparison
    today.setHours(0, 0, 0, 0);

    if (selectedDate < today) {
        alert("You cannot select a past date for disposal.");
        this.value = "<?php echo date('Y-m-d'); ?>"; // Reset to today
    }
});
</script>

</body>
</html>
