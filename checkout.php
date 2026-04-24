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

<div class="checkout-container" style="display: grid; grid-template-columns: 1fr 350px; gap: 30px; margin-top: 20px;">
    <div class="checkout-form-side">
        <?php if ($message): ?>
            <div class="empty-state" style="text-align: center; padding: 40px; background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                <div style="font-size: 3rem; margin-bottom: 15px;">✅</div>
                <h3>Cảm ơn bạn!</h3>
                <p><?php echo sanitize($message); ?></p>
                <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: center;">
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
                    <div class="payment-methods" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin: 5px 0 20px;">
                        <label class="payment-option" style="border: 1px solid #d8dde3; padding: 12px; border-radius: 12px; cursor: pointer; display: flex; align-items: center; gap: 12px; position: relative;">
                            <input type="radio" name="payment_method" value="cod" checked style="width: 18px; height: 18px; margin: 0; cursor: pointer;">
                            <div>
                                <strong style="display: block; font-size: 0.95rem;">Tiền mặt (COD)</strong>
                                <small style="display: block; color: #777; font-size: 0.8rem;">Thanh toán khi nhận hàng</small>
                            </div>
                        </label>
                        <label class="payment-option" style="border: 1px solid #d8dde3; padding: 12px; border-radius: 12px; cursor: pointer; display: flex; align-items: center; gap: 12px; position: relative;">
                            <input type="radio" name="payment_method" value="bank_transfer" style="width: 18px; height: 18px; margin: 0; cursor: pointer;">
                            <div>
                                <strong style="display: block; font-size: 0.95rem;">Chuyển khoản</strong>
                                <small style="display: block; color: #777; font-size: 0.8rem;">Thanh toán qua ngân hàng</small>
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
        <div class="summary-box" style="background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); position: sticky; top: 20px;">
            <h3 style="margin-bottom: 15px;">Tóm tắt đơn hàng</h3>
            <div class="summary-items" style="margin-bottom: 15px;">
                <?php 
                $subtotal_all = 0;
                foreach ($cart as $item): 
                    $sub = $item['price'] * $item['quantity'];
                    $subtotal_all += $sub;
                ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.9rem;">
                        <span style="color: #666;"><?php echo sanitize($item['name']); ?> x<?php echo $item['quantity']; ?></span>
                        <strong><?php echo formatPrice($sub); ?></strong>
                    </div>
                <?php endforeach; ?>
            </div>
            <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">
            <div style="display: flex; justify-content: space-between; font-size: 1.1rem;">
                <span>Tổng tiền:</span>
                <strong style="color: var(--primary);"><?php echo formatPrice($subtotal_all); ?></strong>
            </div>
            <p style="font-size: 0.8rem; color: #999; margin-top: 15px;">* Giá đã bao gồm thuế VAT và phí vận chuyển mặc định.</p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
