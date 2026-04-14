<?php
require_once __DIR__ . "/../config/auth_guard.php";
require_once __DIR__ . "/../config/db.php";

$errors = [];

/**
 * Helper: Fetch dropdown rows into arrays so we can reuse them after POST
 */
function fetch_options(mysqli $conn, string $table): array {
  $rows = [];
  $res = $conn->query("SELECT id, name FROM {$table} ORDER BY name ASC");
  if ($res) {
    while ($r = $res->fetch_assoc()) $rows[] = $r;
  }
  return $rows;
}

$education_levels = fetch_options($conn, "education_levels");
$duty_stations    = fetch_options($conn, "duty_stations");
$languages        = fetch_options($conn, "languages");
$softwares        = fetch_options($conn, "software_expertise");

/**
 * Defaults so form keeps values if validation fails
 */
$form = [
  "index_number" => "",
  "full_names" => "",
  "email" => "",
  "current_location" => "",
  "highest_education_id" => "",
  "duty_station_id" => "",
  "availability_remote_work" => "No",
  "software_expertise_id" => "",
  "software_expertise_level" => "",
  "language_id" => "",
  "level_of_responsibility" => "",
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  foreach ($form as $k => $v) {
    $form[$k] = trim($_POST[$k] ?? $v);
  }

  // Validate required
  if ($form["index_number"] === "") $errors[] = "Index Number is required.";
  if ($form["full_names"] === "") $errors[] = "Full Names is required.";
  if ($form["email"] === "" || !filter_var($form["email"], FILTER_VALIDATE_EMAIL)) $errors[] = "Valid Email is required.";
  if ($form["current_location"] === "") $errors[] = "Current Location is required.";
  if ($form["availability_remote_work"] !== "Yes" && $form["availability_remote_work"] !== "No") {
    $errors[] = "Availability For Remote Work must be Yes or No.";
  }

  // Normalize dropdowns: empty string to NULL
  $highest_education_id  = ($form["highest_education_id"] === "" ? null : (int)$form["highest_education_id"]);
  $duty_station_id       = ($form["duty_station_id"] === "" ? null : (int)$form["duty_station_id"]);
  $software_expertise_id = ($form["software_expertise_id"] === "" ? null : (int)$form["software_expertise_id"]);
  $language_id           = ($form["language_id"] === "" ? null : (int)$form["language_id"]);

  $software_expertise_level = ($form["software_expertise_level"] === "" ? null : $form["software_expertise_level"]);
  $level_of_responsibility  = ($form["level_of_responsibility"] === "" ? null : $form["level_of_responsibility"]);

  if (empty($errors)) {
    $stmt = $conn->prepare("
      INSERT INTO staff (
        index_number,
        full_names,
        email,
        current_location,
        highest_education_id,
        duty_station_id,
        availability_remote_work,
        software_expertise_id,
        software_expertise_level,
        language_id,
        level_of_responsibility,
        updated_at
      ) VALUES (?,?,?,?,?,?,?,?,?,?,?, NOW())
    ");

    if (!$stmt) {
      $errors[] = "Database prepare failed: " . $conn->error;
    } else {
      $index_number = $form["index_number"];
      $full_names = $form["full_names"];
      $email = $form["email"];
      $current_location = $form["current_location"];
      $availability_remote_work = $form["availability_remote_work"];

      // Types: s s s s i i s i s i s
      $stmt->bind_param(
        "ssssiisisis",
        $index_number,
        $full_names,
        $email,
        $current_location,
        $highest_education_id,
        $duty_station_id,
        $availability_remote_work,
        $software_expertise_id,
        $software_expertise_level,
        $language_id,
        $level_of_responsibility
      );

      if ($stmt->execute()) {
        header("Location: /unep-skills-portal/staff/list.php?added=1");
        exit;
      } else {
        if (stripos($stmt->error, "Duplicate") !== false) {
          $errors[] = "Index Number or Email already exists.";
        } else {
          $errors[] = "Failed to save staff: " . $stmt->error;
        }
      }
      $stmt->close();
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Add Staff</title>
  <link rel="stylesheet" href="/unep-skills-portal/assets/css/style.css">
</head>
<body>

  <div class="container">
    <div class="card">

      <div class="topbar">
        <div>
          <h2>Add Staff</h2>
          <div class="small">Fill in the details below and save.</div>
        </div>

        <div class="nav">
          <a href="/unep-skills-portal/index.php">Dashboard</a>
          <a href="/unep-skills-portal/staff/list.php">Staff Listing</a>
        </div>
      </div>

      <hr>

      <?php if (!empty($errors)): ?>
        <div class="alert error">
          <strong>Please fix the following:</strong>
          <ul>
            <?php foreach ($errors as $e): ?>
              <li><?php echo htmlspecialchars($e); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="POST" class="form">

        <div class="field">
          <label>Index Number</label>
          <input type="text" name="index_number" required value="<?php echo htmlspecialchars($form["index_number"]); ?>">
        </div>

        <div class="field">
          <label>Full Names</label>
          <input type="text" name="full_names" required value="<?php echo htmlspecialchars($form["full_names"]); ?>">
        </div>

        <div class="field">
          <label>Email</label>
          <input type="email" name="email" required value="<?php echo htmlspecialchars($form["email"]); ?>">
        </div>

        <div class="field">
          <label>Current Location</label>
          <input type="text" name="current_location" required value="<?php echo htmlspecialchars($form["current_location"]); ?>">
        </div>

        <div class="field">
          <label>Highest Level of Education</label>
          <select name="highest_education_id">
            <option value="">. Select .</option>
            <?php foreach ($education_levels as $row): ?>
              <option value="<?php echo (int)$row["id"]; ?>" <?php echo ($form["highest_education_id"] == $row["id"] ? "selected" : ""); ?>>
                <?php echo htmlspecialchars($row["name"]); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field">
          <label>Duty Station</label>
          <select name="duty_station_id">
            <option value="">. Select .</option>
            <?php foreach ($duty_stations as $row): ?>
              <option value="<?php echo (int)$row["id"]; ?>" <?php echo ($form["duty_station_id"] == $row["id"] ? "selected" : ""); ?>>
                <?php echo htmlspecialchars($row["name"]); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field">
          <label>Availability For Remote Work</label>
          <select name="availability_remote_work" required>
            <option value="Yes" <?php echo ($form["availability_remote_work"] === "Yes" ? "selected" : ""); ?>>Yes</option>
            <option value="No"  <?php echo ($form["availability_remote_work"] === "No"  ? "selected" : ""); ?>>No</option>
          </select>
        </div>

        <div class="field">
          <label>Software Expertise</label>
          <select name="software_expertise_id">
            <option value="">. Select .</option>
            <?php foreach ($softwares as $row): ?>
              <option value="<?php echo (int)$row["id"]; ?>" <?php echo ($form["software_expertise_id"] == $row["id"] ? "selected" : ""); ?>>
                <?php echo htmlspecialchars($row["name"]); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field">
          <label>Software Expertise Level</label>
          <select name="software_expertise_level">
            <option value="">. Select .</option>
            <?php
              $levels = ["Beginner","Intermediate","Advanced","Expert"];
              foreach ($levels as $lvl):
            ?>
              <option value="<?php echo $lvl; ?>" <?php echo ($form["software_expertise_level"] === $lvl ? "selected" : ""); ?>>
                <?php echo $lvl; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field">
          <label>Language</label>
          <select name="language_id">
            <option value="">. Select .</option>
            <?php foreach ($languages as $row): ?>
              <option value="<?php echo (int)$row["id"]; ?>" <?php echo ($form["language_id"] == $row["id"] ? "selected" : ""); ?>>
                <?php echo htmlspecialchars($row["name"]); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field">
          <label>Level of Responsibility</label>
          <select name="level_of_responsibility">
            <option value="">. Select .</option>
            <?php
              $roles = ["Junior","Mid","Senior","Manager","Director"];
              foreach ($roles as $r):
            ?>
              <option value="<?php echo $r; ?>" <?php echo ($form["level_of_responsibility"] === $r ? "selected" : ""); ?>>
                <?php echo $r; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="actions" style="grid-column: 1 / -1; display:flex; justify-content:flex-end; gap:10px; margin-top:15px;">
          <a href="/unep-skills-portal/staff/list.php" class="btn">Cancel</a>
          <button type="submit" class="btn primary">Save Staff</button>
        </div>

      </form>

    </div>
  </div>

</body>
</html>
