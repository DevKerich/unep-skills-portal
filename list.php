<?php
require_once __DIR__ . "/../config/auth_guard.php";
require_once __DIR__ . "/../config/db.php";

// Fetch staff
$sql = "
SELECT 
  s.id,
  s.index_number,
  s.full_names,
  s.email,
  s.current_location,
  e.name AS education,
  d.name AS duty_station
FROM staff s
LEFT JOIN education_levels e ON s.highest_education_id = e.id
LEFT JOIN duty_stations d ON s.duty_station_id = d.id
ORDER BY s.created_at DESC
";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Staff List</title>
  <style>
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 8px; }
    th { background: #f2f2f2; }
  </style>
</head>
<body>

<h2>Staff Listing</h2>

<p>
  <a href="/unep-skills-portal/index.php">Dashboard</a> |
  <a href="/unep-skills-portal/staff/add.php">Add Staff</a>
</p>

<table>
  <tr>
    <th>Index No</th>
    <th>Full Names</th>
    <th>Email</th>
    <th>Location</th>
    <th>Education</th>
    <th>Duty Station</th>
    <th>Actions</th>
  </tr>

  <?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($row["index_number"]); ?></td>
        <td><?php echo htmlspecialchars($row["full_names"]); ?></td>
        <td><?php echo htmlspecialchars($row["email"]); ?></td>
        <td><?php echo htmlspecialchars($row["current_location"]); ?></td>
        <td><?php echo htmlspecialchars($row["education"] ?? ""); ?></td>
        <td><?php echo htmlspecialchars($row["duty_station"] ?? ""); ?></td>
        <td>
          <a href="edit.php?id=<?php echo $row["id"]; ?>">Edit</a> |
          <a href="delete.php?id=<?php echo $row["id"]; ?>"
             onclick="return confirm('Delete this staff record?');">
             Delete
          </a>
        </td>
      </tr>
    <?php endwhile; ?>
  <?php else: ?>
    <tr>
      <td colspan="7">No staff records found.</td>
    </tr>
  <?php endif; ?>

</table>

</body>
</html>
