<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Management</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <link rel="stylesheet" href="../style.css">

  <style>
    .user-table {
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.06);
      overflow: hidden;
    }

    .user-table th {
      background: #f8fafc;
      font-size: 13px;
      text-transform: uppercase;
      color: #6b7280;
    }

    .avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #eef2ff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      color: #4338ca;
      margin-right: 12px;
    }

    .role-badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 13px;
    }

    .role-manager { background: #e0e7ff; color: #3730a3; }
    .role-pharmacist { background: #e0f2fe; color: #0369a1; }
    .role-pending { background: #f3f4f6; color: #374151; }

    .status-badge {
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 13px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .status-active { background: #e0f2fe; color: #0369a1; }
    .status-inactive { background: #fef3c7; color: #92400e; }

    .status-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: currentColor;
    }

    .action-icons i {
      font-size: 18px;
      margin-right: 12px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<!-- SIDEBAR -->
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

  <a href="../../backend/logout.php" class="btn btn-danger">Logout</a>
</div>

<!-- CONTENT -->
<div class="content">

  <h3>User Management</h3>
  <p class="text-muted mb-4">Manage access and roles for pharmacy staff.</p>

  <div class="user-table p-3">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>User</th>
          <th>Role</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>

      <tbody>
        <?php
        include "../../backend/Manager/db_connect.php";
        $result = mysqli_query($conn, "SELECT * FROM user");

        while ($row = mysqli_fetch_assoc($result)) {
            // Change the check from == 1 to == 'ACTIVE'
            $statusClass = (strtoupper($row['accountStatus']) == 'ACTIVE') ? 'status-active' : 'status-inactive';
            $statusText = (strtoupper($row['accountStatus']) == 'ACTIVE') ? 'Active' : 'Inactive';
            
            $roleClass = 'role-' . strtolower($row['role']);
            $firstLetter = strtoupper(substr($row['username'], 0, 1));
        ?>
          <tr>
            <td>
              <div class="d-flex align-items-center">
                <div class="avatar"><?php echo $firstLetter; ?></div>
                <div>
                  <strong><?php echo $row['username']; ?></strong><br>
                  <small class="text-muted"><?php echo $row['email']; ?></small>
                </div>
              </div>
            </td>
            <td><span class="role-badge <?php echo $roleClass; ?>"><?php echo $row['role']; ?></span></td>
            <td>
              <span class="status-badge <?php echo $statusClass; ?>">
                <span class="status-dot"></span> <?php echo $statusText; ?>
              </span>
            </td>
            <td class="action-icons">
                <?php 
                $currentRole = strtoupper($row['role']); 

                if ($currentRole == 'PENDING'): ?>
                    <a href="../../backend/Manager/user_action.php?action=approve&id=<?php echo $row['userID']; ?>" 
                      onclick="return confirm('Approve this user as a Pharmacist?')">
                      <i class="bi bi-check-circle text-primary" title="Approve User"></i></a>

                <?php elseif ($currentRole == 'PHARMACIST'): ?>
                    <a href="../../backend/Manager/user_action.php?action=promote&id=<?php echo $row['userID']; ?>" 
                      onclick="return confirm('Promote to Manager?')">
                      <i class="bi bi-arrow-up-circle text-success" title="Promote"></i></a>

                <?php elseif ($currentRole == 'MANAGER'): ?>
                    <a href="../../backend/Manager/user_action.php?action=demote&id=<?php echo $row['userID']; ?>" 
                      onclick="return confirm('Demote to Pharmacist?')">
                      <i class="bi bi-arrow-down-circle text-secondary" title="Demote"></i></a>
                <?php endif; ?>

                <a href="../../backend/Manager/user_action.php?action=toggle&id=<?php echo $row['userID']; ?>">
                    <i class="bi bi-power <?php echo (strtoupper($row['accountStatus']) == 'ACTIVE') ? 'text-warning' : 'text-primary'; ?>"></i>
                </a>
                <a href="../../backend/Manager/user_action.php?action=delete&id=<?php echo $row['userID']; ?>" 
                  onclick="return confirm('Delete permanently?')">
                  <i class="bi bi-trash text-danger"></i>
                </a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

</div>

<script>
function toggleStatus(userId) {
  const row = document.getElementById(userId);
  const badge = row.querySelector('.status-badge');
  const isActive = badge.classList.contains('status-active');

  if (!confirm(`Are you sure you want to ${isActive ? 'deactivate' : 'activate'} this account?`)) return;

  badge.className = isActive
    ? 'status-badge status-inactive'
    : 'status-badge status-active';

  badge.innerHTML = `<span class="status-dot"></span> ${isActive ? 'Inactive' : 'Active'}`;
}

function promoteUser(userId) {
  if (!confirm("Are you sure you want to promote this user to Manager?")) return;

  const row = document.getElementById(userId);
  row.querySelector('.role-badge').className = 'role-badge role-manager';
  row.querySelector('.role-badge').textContent = 'Manager';

  row.querySelector('.action-icons').innerHTML = `
    <i class="bi bi-arrow-down-circle text-secondary"
       onclick="demoteUser('${userId}')"
       title="Demote to Pharmacist"></i>
    <i class="bi bi-x-circle text-warning"
       onclick="toggleStatus('${userId}')"
       title="Deactivate"></i>
    <i class="bi bi-trash text-danger"
       onclick="deleteUser('${userId}')"
       title="Delete"></i>
  `;
}

function demoteUser(userId) {
  if (!confirm("Are you sure you want to demote this manager to Pharmacist?")) return;

  const row = document.getElementById(userId);
  row.querySelector('.role-badge').className = 'role-badge role-pharmacist';
  row.querySelector('.role-badge').textContent = 'Pharmacist';

  row.querySelector('.action-icons').innerHTML = `
    <i class="bi bi-arrow-up-circle text-success"
       onclick="promoteUser('${userId}')"
       title="Promote to Manager"></i>
    <i class="bi bi-x-circle text-warning"
       onclick="toggleStatus('${userId}')"
       title="Deactivate"></i>
    <i class="bi bi-trash text-danger"
       onclick="deleteUser('${userId}')"
       title="Delete"></i>
  `;
}

function deleteUser(userId) {
  if (confirm("Are you sure you want to delete this account?")) {
    document.getElementById(userId).remove();
    alert("User deleted successfully.");
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
</script>


</body>
</html>
