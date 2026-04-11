<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$stmt = $conn->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC');
$stmt->bind_param('i', $_SESSION['user']['id']);
$stmt->execute();
$result = $stmt->get_result();
$pageTitle = 'Đơn hàng của tôi';
include __DIR__ . '/includes/header.php';
?>
<section class="section-title"><h2>Đơn hàng của tôi</h2></section>
<div class="cart-table-wrap"><table class="cart-table"><thead><tr><th>Mã đơn</th><th>Tổng tiền</th><th>Trạng thái</th><th>Ngày tạo</th></tr></thead><tbody>
<?php while ($row = $result->fetch_assoc()): ?>
<tr><td>#<?php echo $row['id']; ?></td><td><?php echo formatPrice($row['total_amount']); ?></td><td><?php echo sanitize($row['status']); ?></td><td><?php echo sanitize($row['created_at']); ?></td></tr>
<?php endwhile; ?>
</tbody></table></div>
<?php include __DIR__ . '/includes/footer.php'; ?>
