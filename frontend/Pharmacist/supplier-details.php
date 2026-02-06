<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Supplier Details</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <link rel="stylesheet" href="../style.css">

  <style>
    .detail-card {
      background: #ffffff;
      border-radius: 16px;
      padding: 32px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.06);
      max-width: 900px;
    }

    .section-title {
      font-size: 14px;
      font-weight: 600;
      text-transform: uppercase;
      color: #64748b;
      margin-bottom: 12px;
    }

    .info-label {
      font-size: 12px;
      color: #64748b;
      text-transform: uppercase;
    }

    .info-value {
      font-size: 15px;
      font-weight: 500;
      color: #111827;
    }
  </style>
</head>
<body>

<!-- ============ SIDEBAR (UNCHANGED) ============ -->
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

<!-- ============ END SIDEBAR ============ -->


<!-- ============ CONTENT ============ -->
<?php
session_start();
include "../..//backend/db_connect.php";

// 1. Get the ID from the URL
$supplierID = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

// 2. Fetch the specific supplier data from database
$supplier = null;
if (!empty($supplierID)) {
    $sql = "SELECT * FROM supplier WHERE supplierID = '$supplierID'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $supplier = mysqli_fetch_assoc($result);
    }
}

// 3. If no supplier found, go back
if (!$supplier) {
    echo "<script>alert('Supplier not found!'); window.location.href='product-list.php';</script>";
    exit();
}
?>

<div class="content d-flex justify-content-center pt-4">
  <div class="detail-card w-100">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold mb-0">Supplier Details</h4>
      <a href="product-list.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
    </div>

    <div class="mb-4">
      <div class="section-title">Supplier Information</div>
      <div class="row mb-3">
        <div class="col-md-6">
          <div class="info-label">Supplier Name</div>
          <div class="info-value"><?php echo $supplier['supplierName']; ?></div>
        </div>
        <div class="col-md-6">
          <div class="info-label">Supplier Email</div>
          <div class="info-value"><?php echo $supplier['supplierEmail']; ?></div>
        </div>
      </div>
    </div>

    <div>
      <div class="section-title">Person In Charge (PIC)</div>
      <div class="row mb-3">
        <div class="col-md-6">
          <div class="info-label">PIC Name</div>
          <div class="info-value"><?php echo $supplier['picName']; ?></div>
        </div>
        <div class="col-md-6">
          <div class="info-label">PIC Phone Number</div>
          <div class="info-value"><?php echo $supplier['picPhoneNum']; ?></div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="info-label">PIC Email</div>
          <div class="info-value"><?php echo $supplier['picEmail']; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- ============ END CONTENT ============ -->

<script>
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
</script>

</body>
</html>
