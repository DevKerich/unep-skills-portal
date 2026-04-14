<?php
session_start();

if (!isset($_SESSION["user"])) {
  header("Location: /unep-skills-portal/auth/login.php");
  exit;
}
?>
