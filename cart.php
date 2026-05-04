<?php
require_once __DIR__ . '/includes/functions.php';

if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['id'])) {
    $remove_id = $_GET['id'];
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
    }
    header('Location: /Web/cart.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['qty'] ?? [] as $id => $qty) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = max(1, (int) $qty);
        }
    }
}
$cart = $_SESSION['cart'] ?? [];
$total = 0;
$pageTitle = 'Giỏ hàng';
include __DIR__ . '/includes/header.php';
?>
<section class="section-headline compact">
    <div>
        <span class="section-kicker">Giỏ hàng của bạn</span>
        <h2>Kiểm tra sản phẩm trước khi thanh toán</h2>
    </div>
</section>

<?php if (empty($cart)): ?>
    <div class="empty-state empty-card">
        <div class="empty-icon">🛒</div>
        <h3>Giỏ hàng của bạn đang trống</h3>
        <p class="muted-text">Có vẻ như bạn chưa chọn được sản phẩm nào ưng ý.</p>
        <a href="products.php" class="btn primary">Tiếp tục mua sắm</a>
    </div>
<?php else: ?>
    <form method="POST" class="cart-table-wrap modern-cart">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $item): ?>
                    <?php 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal; 
                    ?>
                    <tr>
                        <td>
                            <div class="cart-product-cell">
                                <?php if(!empty($item['image'])): ?>
                                    <img src="<?php echo imageUrl($item['image']); ?>" width="60" class="cart-thumb" alt="<?php echo sanitize($item['name']); ?>">
                                <?php endif; ?>
                                <strong><?php echo sanitize($item['name']); ?></strong>
                            </div>
                        </td>
                        <td><?php echo formatPrice($item['price']); ?></td>
                        <td>
                            <input type="number" min="1" name="qty[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" style="width: 70px;">
                        </td>
                        <td><strong><?php echo formatPrice($subtotal); ?></strong></td>
                        <td>
                            <a href="?action=remove&amp;id=<?php echo $item['id']; ?>" class="btn small danger" onclick="return confirm('Bỏ sản phẩm này khỏi giỏ hàng?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="cart-actions-footer summary-card">
            <div class="total-info">
                <span class="muted-text">Tổng số tiền:</span>
                <div class="total-price-emphasis"><?php echo formatPrice($total); ?></div>
            </div>
            <div class="cart-footer-actions">
                <button class="btn light" type="submit">Cập nhật giỏ</button> 
                <a class="btn primary" href="/Web/checkout.php">Thanh toán ngay</a>
            </div>
        </div>
    </form>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>