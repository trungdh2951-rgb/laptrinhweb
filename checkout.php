<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

requireLogin();

$cart = $_SESSION['cart'] ?? [];
if (empty($cart) && !isset($_GET['success'])) {
    header('Location: /Web/cart.php');
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $payment_method = $_POST['payment_method'] ?? 'cod';
    $note = trim($_POST['note'] ?? '');
    
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare('INSERT INTO orders (user_id, total_amount, shipping_address, payment_method, phone, note) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('idssss', $_SESSION['user']['id'], $total, $address, $payment_method, $phone, $note);
        $stmt->execute();
        $orderId = $stmt->insert_id;

        $itemStmt = $conn->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
        foreach ($cart as $item) {
            $itemStmt->bind_param('iiid', $orderId, $item['id'], $item['quantity'], $item['price']);
            $itemStmt->execute();
            
            // Update stock
            $conn->query("UPDATE products SET stock = stock - {$item['quantity']} WHERE id = {$item['id']}");
        }

        $conn->commit();
        unset($_SESSION['cart']);
        $message = 'Đặt hàng thành công! Mã đơn hàng của bạn là #' . $orderId;
    } catch (Exception $e) {
        $conn->rollback();
        $message = 'Có lỗi xảy ra: ' . $e->getMessage();
    }
}

$pageTitle = 'Thanh toán';
include __DIR__ . '/includes/header.php';
?>
<section class="section-headline compact">
    <div>
        <span class="section-kicker">Hoàn tất đơn hàng</span>
        <h2>Thông tin thanh toán & Giao hàng</h2>
    </div>
</section>

<div class="checkout-container">
    <div class="checkout-form-side">
        <?php if ($message): ?>
            <div class="empty-state empty-card">
                <div class="empty-icon">✅</div>
                <h3>Cảm ơn bạn!</h3>
                <p><?php echo sanitize($message); ?></p>
                <div class="center-actions">
                    <a href="index.php" class="btn primary">Về trang chủ</a>
                    <a href="orders.php" class="btn light">Xem đơn hàng</a>
                </div>
            </div>
        <?php else: ?>
            <div class="auth-box" style="width: 100%; max-width: 100%; margin: 0;">
                <h3>Thông tin người nhận</h3>
                <form method="POST" class="auth-form wide">
                    <label>Địa chỉ nhận hàng</label>
                    <input type="text" name="address" placeholder="Số nhà, tên đường, phường/xã..." required>
                    
                    <label>Số điện thoại liên hệ</label>
                    <input type="text" name="phone" value="<?php echo sanitize($_SESSION['user']['phone'] ?? ''); ?>" placeholder="Số điện thoại" required>
                    
                    <label>Phương thức thanh toán</label>
                    <div class="payment-methods">
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="cod" checked>
                            <div>
                                <strong>Tiền mặt (COD)</strong>
                                <small>Thanh toán khi nhận hàng</small>
                            </div>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="bank_transfer">
                            <div>
                                <strong>Chuyển khoản</strong>
                                <small>Thanh toán qua ngân hàng</small>
                            </div>
                        </label>
                    </div>

                    <label>Ghi chú (tùy chọn)</label>
                    <textarea name="note" placeholder="Lời nhắn cho shipper..."></textarea>
                    
                    <button class="btn primary full" type="submit">Xác nhận đặt hàng</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <div class="checkout-summary-side">
        <div class="summary-box summary-card">
            <h3 class="summary-title">Tóm tắt đơn hàng</h3>
            <div class="summary-items">
                <?php 
                $subtotal_all = 0;
                foreach ($cart as $item): 
                    $sub = $item['price'] * $item['quantity'];
                    $subtotal_all += $sub;
                ?>
                    <div class="summary-row">
                        <span class="muted-text"><?php echo sanitize($item['name']); ?> x<?php echo $item['quantity']; ?></span>
                        <strong><?php echo formatPrice($sub); ?></strong>
                    </div>
                <?php endforeach; ?>
            </div>
            <hr class="summary-divider">
            <div class="summary-total-row">
                <span>Tổng tiền:</span>
                <strong class="total-price-emphasis"><?php echo formatPrice($subtotal_all); ?></strong>
            </div>
            <p class="summary-note">* Giá đã bao gồm thuế VAT và phí vận chuyển mặc định.</p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
