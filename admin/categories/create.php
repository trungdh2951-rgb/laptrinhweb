<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { $name = trim($_POST['name'] ?? ''); $description = trim($_POST['description'] ?? ''); $stmt = $conn->prepare('INSERT INTO categories (name, description) VALUES (?, ?)'); $stmt->bind_param('ss', $name, $description); $stmt->execute(); header('Location: /Web/admin/categories/index.php'); exit(); }
include __DIR__ . '/../../includes/header.php';
?>
<section class="auth-box"><h2>Thêm danh mục</h2><form method="POST" class="auth-form"><input type="text" name="name" placeholder="Tên danh mục" required><textarea name="description" placeholder="Mô tả"></textarea><button class="btn primary" type="submit">Lưu</button></form></section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
