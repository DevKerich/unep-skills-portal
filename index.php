<?php
require_once __DIR__ . "/config/auth_guard.php";
$user = $_SESSION["user"];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dashboard . UNEP Skills Portal</title>
  <link rel="stylesheet" href="/unep-skills-portal/assets/css/style.css">
</head>
<body>

  <div class="container">
    <div class="card">

      <div class="topbar">
        <div>
          <h2>Dashboard</h2>
          <div class="small">
            Welcome, <?php echo htmlspecialchars($user["full_name"]); ?>
            (<?php echo htmlspecialchars($user["role"]); ?>)
          </div>
        </div>

        <div class="nav">
          <a href="/unep-skills-portal/staff/list.php">Staff Listing</a>
          <a href="/unep-skills-portal/staff/add.php">Add Staff</a>
          <a href="/unep-skills-portal/auth/logout.php">Logout</a>
        </div>
      </div>

      <hr>

      <p class="small">Use the links above to manage staff records and view listings.</p>

    </div>
  </div>

</body>
</html>
