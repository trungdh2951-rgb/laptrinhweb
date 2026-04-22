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
    <div class="empty-box">Giỏ hàng của bạn đang trống.</div>
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
                    <?php $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal; ?>
                    <tr>
                        <td><?php echo sanitize($item['name']); ?></td>
                        <td><?php echo formatPrice($item['price']); ?></td>
                        <td><input type="number" min="1" name="qty[<?php echo $item['id']; ?>]"
                                value="<?php echo $item['quantity']; ?>"></td>
                        <td><?php echo formatPrice($subtotal); ?></td>
                        <td><a href="?action=remove&amp;id=<?php echo $item['id']; ?>" class="btn small danger" onclick="return confirm('Bỏ sản phẩm này khỏi giỏ hàng?');">Xóa</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="cart-actions"><strong>Tổng cộng: <?php echo formatPrice($total); ?></strong>
            <div><button class="btn light" type="submit">Cập nhật giỏ</button> <a class="btn primary"
                    href="/Web/checkout.php">Thanh toán</a></div>
        </div>
    </form>
<?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>