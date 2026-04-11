<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
$items = $conn->query('SELECT * FROM categories ORDER BY id DESC');
include __DIR__ . '/../../includes/header.php';
?>
<section class="section-title"><h2>Quản lý danh mục</h2></section>
<a class="btn primary" href="/Web/admin/categories/create.php">Thêm danh mục</a>
<div class="cart-table-wrap"><table class="cart-table"><thead><tr><th>ID</th><th>Tên</th><th>Mô tả</th><th>Thao tác</th></tr></thead><tbody>
<?php while ($row = $items->fetch_assoc()): ?>
<tr><td><?php echo $row['id']; ?></td><td><?php echo sanitize($row['name']); ?></td><td><?php echo sanitize($row['description']); ?></td><td><a class="btn small" href="/Web/admin/categories/edit.php?id=<?php echo $row['id']; ?>">Sửa</a> <a class="btn small danger" href="/Web/admin/categories/delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Xóa danh mục này?')">Xóa</a></td></tr>
<?php endwhile; ?>
</tbody></table></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
