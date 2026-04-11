<?php
require_once __DIR__ . '/../../config/database.php'; require_once __DIR__ . '/../../includes/functions.php'; require_once __DIR__ . '/../../includes/auth.php'; requireAdmin(); $items = $conn->query('SELECT id, full_name, email, phone, role, created_at FROM users ORDER BY id DESC'); include __DIR__ . '/../../includes/header.php';
?>
<section class="section-title"><h2>Quản lý người dùng</h2></section>
<div class="cart-table-wrap"><table class="cart-table"><thead><tr><th>ID</th><th>Họ tên</th><th>Email</th><th>SĐT</th><th>Vai trò</th><th>Ngày tạo</th><th>Thao tác</th></tr></thead><tbody>
<?php while ($row = $items->fetch_assoc()): ?>
<tr><td><?php echo $row['id']; ?></td><td><?php echo sanitize($row['full_name']); ?></td><td><?php echo sanitize($row['email']); ?></td><td><?php echo sanitize($row['phone']); ?></td><td><?php echo sanitize($row['role']); ?></td><td><?php echo sanitize($row['created_at']); ?></td><td><a class="btn small danger" href="/Web/admin/users/delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Xóa người dùng này?')">Xóa</a></td></tr>
<?php endwhile; ?>
</tbody></table></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
