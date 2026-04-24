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
    <div class="empty-state" style="text-align: center; padding: 60px 20px; background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin: 20px 0;">
        <div style="font-size: 4rem; margin-bottom: 15px;">🛒</div>
        <h3>Giỏ hàng của bạn đang trống</h3>
        <p style="color: #666; margin-bottom: 25px;">Có vẻ như bạn chưa chọn được sản phẩm nào ưng ý.</p>
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
                        <td style="display: flex; align-items: center; gap: 15px;">
                            <?php if(!empty($item['image'])): ?>
                                <img src="/Web/image/<?php echo sanitize($item['image']); ?>" width="60" style="border-radius: 8px;">
                            <?php endif; ?>
                            <strong><?php echo sanitize($item['name']); ?></strong>
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
        
        <div class="cart-actions-footer" style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <div class="total-info">
                <span style="color: #666;">Tổng số tiền:</span>
                <div style="font-size: 1.5rem; color: var(--primary); font-weight: 700;"><?php echo formatPrice($total); ?></div>
            </div>
            <div style="display: flex; gap: 10px;">
                <button class="btn light" type="submit">Cập nhật giỏ</button> 
                <a class="btn primary" href="/Web/checkout.php">Thanh toán ngay</a>
            </div>
        </div>
    </form>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>