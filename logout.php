<?php
session_start();
session_destroy();

header("Location: /unep-skills-portal/auth/login.php");
exit;
