<?php
require_once __DIR__ . '/../../config/database.php'; require_once __DIR__ . '/../../includes/auth.php'; requireAdmin(); $id = (int)($_GET['id'] ?? 0); $status = $_GET['status'] ?? 'pending'; $allowed = ['pending', 'processing', 'completed', 'cancelled']; if (!in_array($status, $allowed, true)) { $status = 'pending'; } $stmt = $conn->prepare('UPDATE orders SET status = ? WHERE id = ?'); $stmt->bind_param('si', $status, $id); $stmt->execute(); header('Location: /Web/admin/orders/index.php'); exit();
?>
