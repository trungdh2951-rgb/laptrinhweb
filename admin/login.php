<?php
session_start();
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') { header('Location: /Web/admin/index.php'); exit(); }
header('Location: /Web/login.php'); exit();
?>
