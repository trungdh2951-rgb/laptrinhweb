<?php
require_once __DIR__ . '/../../config/database.php'; require_once __DIR__ . '/../../includes/auth.php'; requireAdmin(); $id = (int)($_GET['id'] ?? 0); $stmt = $conn->prepare('DELETE FROM users WHERE id = ? AND role != "admin"'); $stmt->bind_param('i', $id); $stmt->execute(); header('Location: /Web/admin/users/index.php'); exit();
?>
