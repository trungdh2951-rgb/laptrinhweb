<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();
$id = (int)($_GET['id'] ?? 0); $stmt = $conn->prepare('SELECT * FROM categories WHERE id = ?'); $stmt->bind_param('i', $id); $stmt->execute(); $item = $stmt->get_result()->fetch_assoc(); if (!$item) die('Không tìm thấy danh mục.');
if ($_SERVER['REQUEST_METHOD'] === 'POST') { $name = trim($_POST['name'] ?? ''); $description = trim($_POST['description'] ?? ''); $update = $conn->prepare('UPDATE categories SET name = ?, description = ? WHERE id = ?'); $update->bind_param('ssi', $name, $description, $id); $update->execute(); header('Location: /Web/admin/categories/index.php'); exit(); }
include __DIR__ . '/../../includes/header.php';
?>
<section class="auth-box"><h2>Sửa danh mục</h2><form method="POST" class="auth-form"><input type="text" name="name" value="<?php echo sanitize($item['name']); ?>" required><textarea name="description"><?php echo sanitize($item['description']); ?></textarea><button class="btn primary" type="submit">Cập nhật</button></form></section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
