<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare('SELECT products.*, categories.name AS category_name FROM products LEFT JOIN categories ON products.category_id = categories.id WHERE products.id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die('Sản phẩm không tồn tại.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qty = max(1, (int) ($_POST['quantity'] ?? 1));
    $_SESSION['cart'][$id] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $qty,
    ];
    header('Location: /Web/cart.php');
    exit();
}

$pageTitle = $product['name'];
include __DIR__ . '/includes/header.php';
?>
<div class="detail-card shop-detail-card">
    <div class="detail-media">
        <div class="detail-thumb">
            <?php if(!empty($product['image'])): ?>
                <img src="/Web/image/<?php echo sanitize($product['image']); ?>" alt="<?php echo sanitize($product['name']); ?>" class="product-entry-img">
            <?php else: ?>
                <?php echo sanitize($product['name']); ?>
            <?php endif; ?>
        </div>
        <div class="detail-feature-list">
            <span>Hàng chính hãng</span>
            <span>Bảo hành minh hoạ</span>
            <span>Hỗ trợ đổi trả</span>
        </div>
    </div>
    <div class="detail-content">
        <span class="badge"><?php echo sanitize($product['category_name'] ?? 'Chưa phân loại'); ?></span>
        <h1><?php echo sanitize($product['name']); ?></h1>
        <p class="detail-description"><?php echo sanitize($product['description']); ?></p>
        <div class="detail-price-box">
            <strong><?php echo formatPrice($product['price']); ?></strong>
            <span>Còn lại <?php echo (int) $product['stock']; ?> sản phẩm</span>
        </div>
        <form method="POST" class="purchase-box">
            <label>Số lượng</label>
            <input type="number" name="quantity" min="1" value="1">
            <button class="btn primary" type="submit">Thêm vào giỏ</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>