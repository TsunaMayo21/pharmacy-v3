<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product List</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Your existing CSS -->
  <link rel="stylesheet" href="../style.css">

  <style>
    /* Inventory header */
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
    }

    .page-header h3 {
      margin-bottom: 4px;
    }

    /* Search bar */
    .search-bar {
      display: flex;
      gap: 10px;
      margin-bottom: 16px;
    }

    .search-bar input {
      max-width: 360px;
    }

    /* Inventory table */
    .inventory-table th {
      background-color: #f8fafc;
      font-size: 14px;
      text-transform: uppercase;
      color: #6b7280;
    }

    .inventory-table td {
      vertical-align: middle;
      font-size: 14px;
    }

    /* Status badges */
    .status-normal {
      background: #e0f2fe;
      color: #0369a1;
    }

    .status-expiring {
      background: #fef3c7;
      color: #92400e;
    }

    .status-expired {
      background: #fee2e2;
      color: #991b1b;
    }
  </style>
</head>
<body>

<!-- ================= SIDEBAR ================= -->
<div class="sidebar">
  <h3>HH IMS</h3>

  <a href="dashboard.html" class="nav-link">Dashboard</a>

  <button class="dropdown-btn">Account ▾</button>
  <div class="dropdown-container">
    <a href="account-profile.html" class="nav-link">My Profile</a>
    <a href="account-management.php" class="nav-link">User Management</a>
  </div>

  <button class="dropdown-btn">Products ▾</button>
  <div class="dropdown-container">
    <a href="product-list.php" class="nav-link">Product List</a>
  </div>

  <button class="dropdown-btn">Reports ▾</button>
  <div class="dropdown-container">
    <a href="report-view.html" class="nav-link">View Report</a>
  </div>

  <a href="../../backend/logout.php" class="btn btn-danger mt-3">Logout</a>
</div>
<!-- ================= END SIDEBAR ================= -->


<!-- ================= CONTENT ================= -->
<div class="content">

  <div class="page-header">
    <div>
      <h3>Inventory</h3>
      <p class="text-muted mb-0">Manage stock, track expiry, and handle disposals.</p>
    </div>
  </div>

  <div class="search-bar">
    <input type="text" id="searchInput" class="form-control" placeholder="Search product, brand or batch...">
    <button class="btn btn-outline-secondary">
      <i class="bi bi-funnel"></i>
    </button>
  </div>

  <div class="user-table p-3">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Product Name</th>
          <th>Brand</th>
          <th>Batch</th>
          <th>Qty</th>
          <th>Supplier</th>
          <th>Expiry</th>
          <th>Status</th>
        </tr>
      </thead>

      <tbody id="inventoryTable">
        <?php
        include "../../backend/db_connect.php";

        $sql = "SELECT p.productID, p.productName, p.brandName, pi.batchNumber, 
                       pi.quantity, pi.expiryDate, s.supplierName, s.supplierID
                FROM product p
                JOIN productitem pi ON p.productID = pi.productID
                JOIN supplier s ON p.supplierID = s.supplierID
                ORDER BY pi.expiryDate ASC";

        $result = mysqli_query($conn, $sql);

        if (!$result) {
          die("Query Failed: " . mysqli_error($conn));
        }

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
        ?>
        <tr>
          <td><strong><?php echo $row['productName']; ?></strong></td>
          <td><?php echo $row['brandName']; ?></td>
          <td><code><?php echo $row['batchNumber']; ?></code></td>
          <td><?php echo $row['quantity']; ?></td>
          <td>
            <?php if (!empty($row['supplierID'])): ?>
              <a href="supplier-details.php?id=<?php echo $row['supplierID']; ?>"
                 class="text-decoration-none fw-bold text-primary">
                <?php echo $row['supplierName']; ?>
              </a>
            <?php else: ?>
              <span class="text-danger"><?php echo $row['supplierName']; ?> (No ID)</span>
            <?php endif; ?>
          </td>
          <td><?php echo date('d M Y', $expiry); ?></td>
          <td><?php echo $status; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ================= SCRIPTS ================= -->
<script>
document.querySelectorAll(".dropdown-btn").forEach(btn => {
  btn.onclick = () => {
    const menu = btn.nextElementSibling;
    menu.style.display = menu.style.display === "block" ? "none" : "block";
  };
});
</script>

<script>
function fetchResults() {
  let keyword = document.getElementById("searchInput").value;
  let tableBody = document.getElementById("inventoryTable");

  fetch(`../../backend/Manager/search_handler.php?keyword=${encodeURIComponent(keyword)}`)
    .then(response => response.text())
    .then(data => {
      tableBody.innerHTML = data;
    })
    .catch(error => console.error("Error fetching data:", error));
}

document.getElementById("searchInput").addEventListener("keyup", fetchResults);
</script>

<script>
const currentPage = window.location.pathname.split("/").pop();

document.querySelectorAll(".sidebar .active").forEach(el => {
  el.classList.remove("active");
});

document.querySelectorAll(".sidebar a.nav-link").forEach(link => {
  if (link.getAttribute("href") === currentPage) {
    link.classList.add("active");
    const dropdown = link.closest(".dropdown-container");
    if (dropdown) dropdown.style.display = "block";
  }
});
</script>

</body>
</html>
