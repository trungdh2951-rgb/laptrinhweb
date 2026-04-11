<?php
require_once __DIR__ . '/../../config/database.php'; require_once __DIR__ . '/../../includes/functions.php'; require_once __DIR__ . '/../../includes/auth.php'; requireAdmin(); $id = (int)($_GET['id'] ?? 0); $orderStmt = $conn->prepare('SELECT orders.*, users.full_name, users.email FROM orders INNER JOIN users ON orders.user_id = users.id WHERE orders.id = ?'); $orderStmt->bind_param('i', $id); $orderStmt->execute(); $order = $orderStmt->get_result()->fetch_assoc(); $itemStmt = $conn->prepare('SELECT order_items.*, products.name FROM order_items INNER JOIN products ON order_items.product_id = products.id WHERE order_items.order_id = ?'); $itemStmt->bind_param('i', $id); $itemStmt->execute(); $items = $itemStmt->get_result(); include __DIR__ . '/../../includes/header.php';
?>
<section class="section-title"><h2>Chi tiết đơn hàng #<?php echo $id; ?></h2></section>
<div class="card-block"><p><strong>Khách hàng:</strong> <?php echo sanitize($order['full_name'] ?? ''); ?></p><p><strong>Email:</strong> <?php echo sanitize($order['email'] ?? ''); ?></p><p><strong>Địa chỉ giao hàng:</strong> <?php echo sanitize($order['shipping_address'] ?? ''); ?></p><p><strong>Ghi chú:</strong> <?php echo sanitize($order['note'] ?? ''); ?></p></div>
<div class="cart-table-wrap"><table class="cart-table"><thead><tr><th>Sản phẩm</th><th>Số lượng</th><th>Giá</th></tr></thead><tbody>
<?php while ($row = $items->fetch_assoc()): ?>
<tr><td><?php echo sanitize($row['name']); ?></td><td><?php echo $row['quantity']; ?></td><td><?php echo formatPrice($row['price']); ?></td></tr>
<?php endwhile; ?>
</tbody></table></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
