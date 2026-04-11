<?php
require_once __DIR__ . '/../../config/database.php'; require_once __DIR__ . '/../../includes/functions.php'; require_once __DIR__ . '/../../includes/auth.php'; requireAdmin(); $sql = 'SELECT orders.*, users.full_name FROM orders INNER JOIN users ON orders.user_id = users.id ORDER BY orders.id DESC'; $items = $conn->query($sql); include __DIR__ . '/../../includes/header.php';
?>
<section class="section-title"><h2>Quản lý đơn hàng</h2></section>
<div class="cart-table-wrap"><table class="cart-table"><thead><tr><th>Mã đơn</th><th>Khách hàng</th><th>Tổng tiền</th><th>Trạng thái</th><th>Ngày tạo</th><th>Thao tác</th></tr></thead><tbody>
<?php while ($row = $items->fetch_assoc()): ?>
<tr><td>#<?php echo $row['id']; ?></td><td><?php echo sanitize($row['full_name']); ?></td><td><?php echo formatPrice($row['total_amount']); ?></td><td><?php echo sanitize($row['status']); ?></td><td><?php echo sanitize($row['created_at']); ?></td><td><a class="btn small" href="/Web/admin/orders/detail.php?id=<?php echo $row['id']; ?>">Chi tiết</a> <a class="btn small" href="/Web/admin/orders/update_status.php?id=<?php echo $row['id']; ?>&status=processing">Đang xử lý</a> <a class="btn small" href="/Web/admin/orders/update_status.php?id=<?php echo $row['id']; ?>&status=completed">Hoàn thành</a></td></tr>
<?php endwhile; ?>
</tbody></table></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
