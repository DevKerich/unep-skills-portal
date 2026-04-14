<?php
session_start();
require_once __DIR__ . "/../config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = trim($_POST["email"] ?? "");
  $password = $_POST["password"] ?? "";

  $stmt = $conn->prepare("SELECT id, full_name, email, password_hash, role FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user && password_verify($password, $user["password_hash"])) {
    $_SESSION["user"] = [
      "id" => $user["id"],
      "full_name" => $user["full_name"],
      "email" => $user["email"],
      "role" => $user["role"]
    ];
    header("Location: /unep-skills-portal/index.php");
    exit;
  } else {
    $error = "Invalid email or password.";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login . UNEP Skills Portal</title>
</head>
<body>
  <h2>UNEP Skills Portal . Login</h2>

  <?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>

  <form method="POST">
    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

  <p>Test Admin Login: admin@unep.org / admin123</p>
</body>
</html>
