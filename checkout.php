<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) { header('Location: /Web/cart.php'); exit(); }
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $note = trim($_POST['note'] ?? '');
    $total = 0; foreach ($cart as $item) { $total += $item['price'] * $item['quantity']; }
    $stmt = $conn->prepare('INSERT INTO orders (user_id, total_amount, shipping_address, phone, note) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('idsss', $_SESSION['user']['id'], $total, $address, $phone, $note);
    $stmt->execute(); $orderId = $stmt->insert_id;
    $itemStmt = $conn->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
    foreach ($cart as $item) { $itemStmt->bind_param('iiid', $orderId, $item['id'], $item['quantity'], $item['price']); $itemStmt->execute(); }
    unset($_SESSION['cart']); $message = 'Đặt hàng thành công!';
}
$pageTitle = 'Thanh toán'; include __DIR__ . '/includes/header.php';
?>
<section class="section-title"><h2>Thanh toán</h2></section>
<?php if ($message): ?>
<div class="alert success"><?php echo sanitize($message); ?></div>
<?php else: ?>
<form method="POST" class="auth-form wide">
<input type="text" name="address" placeholder="Địa chỉ giao hàng" required>
<input type="text" name="phone" placeholder="Số điện thoại" required>
<textarea name="note" placeholder="Ghi chú đơn hàng"></textarea>
<button class="btn primary" type="submit">Xác nhận đặt hàng</button>
</form>
<?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
