<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Product</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Existing layout & sidebar CSS -->
  <link rel="stylesheet" href="../style.css">

  <style>
    .form-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 40px 48px;
      max-width: 900px;
      width: 100%;
      min-height: 560px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.08);
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
  <?php
  include "../../backend/db_connect.php";
  // 1. Get the batch number from URL
  $batch = $_GET['batch'] ?? '';

  // 2. Fetch all related info for this batch
  $sql = "SELECT p.*, pi.*, s.* FROM product p 
          JOIN productitem pi ON p.productID = pi.productID 
          JOIN supplier s ON p.supplierID = s.supplierID 
          WHERE pi.batchNumber = '$batch'";

  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);

  if (!$row) { die("Batch not found."); }
  ?>

  <div class="content d-flex justify-content-center align-items-start pt-5">
    <form action="../../backend/Pharmacist/product_action.php?action=update" method="POST" class="form-card">
      <h3 class="fw-bold mb-4">Update Product</h3>

      <input type="hidden" name="old_batchNumber" value="<?php echo $row['batchNumber']; ?>">
      <input type="hidden" name="productID" value="<?php echo $row['productID']; ?>">
      <input type="hidden" name="supplierID" value="<?php echo $row['supplierID']; ?>">

      <h6 class="fw-semibold mb-3">Product Information</h6>
      <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="productName" class="form-control" value="<?php echo $row['productName']; ?>">
      </div>
      <div class="mb-4">
        <label class="form-label">Brand Name</label>
        <input type="text" name="brandName" class="form-control" value="<?php echo $row['brandName']; ?>">
      </div>

      <h6 class="fw-semibold mb-3">Batch Details</h6>
      <div class="mb-3">
        <label class="form-label">Batch Number</label>
        <input type="text" name="batchNumber" class="form-control" value="<?php echo $row['batchNumber']; ?>">
      </div>
      <div class="row mb-4">
        <div class="row mb-4">
          <div class="col-md-6">
            <label class="form-label">Quantity</label>
            <input
              type="number"
              name="quantity"
              class="form-control"
              value="<?php echo $row['quantity']; ?>"
              min="0"
              step="1"
              onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
              onpaste="return false"
              oninput="if(this.value < 0) this.value = 0;"
              required
            >
          </div>
        <div class="col-md-6">
          <label class="form-label">Expiry Date</label>
          <input type="date" name="expiryDate" class="form-control" value="<?php echo $row['expiryDate']; ?>">
        </div>
      </div>

      <h6 class="fw-semibold mb-3">Supplier Information</h6>
      <div class="mb-3">
        <label class="form-label">Supplier Name</label>
        <input type="text" name="supplierName" class="form-control" value="<?php echo $row['supplierName']; ?>">
      </div>
      <div class="row mb-4">
        <div class="col-md-6">
          <label class="form-label">Supplier Contact</label>
          <input type="text" name="supplierEmail" class="form-control" value="<?php echo $row['supplierEmail']; ?>">
        </div>
      </div>

      <div class="d-flex justify-content-between">
        <a href="product-list.php" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary" onclick="return confirm('Update product details?')">
          Save Changes
        </button>
      </div>
    </form>
  </div>
<!-- ================= END CONTENT ================= -->


<script>
function confirmUpdate() {
  const confirmAction = confirm("Are you sure you want to update this product?");
  if (confirmAction) {
    alert("Product updated successfully (frontend only).");
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

  function validateForm() {
    const qty = document.querySelector('input[name="quantity"]').value;
    const expiry = document.getElementById("expiryDate").value;
    const today = new Date().toISOString().split("T")[0];

    // Double check on submit just in case
    if (qty < 0) {
      alert("Quantity cannot be less than 0.");
      return false;
    }

    if (expiry < today) {
      alert("Expiry date cannot be in the past.");
      return false;
    }

    return true;
  }
</script>

</body>
</html>
