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
        'image' => $product['image'],
        'quantity' => ($_SESSION['cart'][$id]['quantity'] ?? 0) + $qty,
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
                <div class="no-image-detail"><?php echo sanitize($product['name']); ?></div>
            <?php endif; ?>
        </div>
        <div class="detail-feature-list">
            <span>🛡️ Hàng chính hãng 100%</span>
            <span>🚚 Giao hàng toàn quốc</span>
            <span>🔄 Bảo hành uy tín</span>
        </div>
    </div>
    <div class="detail-content">
        <div class="detail-header-meta">
            <span class="badge"><?php echo sanitize($product['category_name'] ?? 'Chưa phân loại'); ?></span>
            <?php if($product['brand']): ?>
                <span class="badge light"><?php echo sanitize($product['brand']); ?></span>
            <?php endif; ?>
        </div>
        
        <h1><?php echo sanitize($product['name']); ?></h1>

        <!-- Tech Specs highlight -->
        <?php if($product['cpu'] || $product['ram']): ?>
            <div class="detail-tech-specs" style="margin-bottom: 20px; display: flex; gap: 15px; background: #f8f9fa; padding: 15px; border-radius: 8px;">
                <?php if($product['cpu']): ?>
                    <div><strong>Vi xử lý:</strong> <?php echo sanitize($product['cpu']); ?></div>
                <?php endif; ?>
                <?php if($product['ram']): ?>
                    <div><strong>Bộ nhớ RAM:</strong> <?php echo sanitize($product['ram']); ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <p class="detail-description"><?php echo nl2br(sanitize($product['description'])); ?></p>
        
        <div class="detail-price-box">
            <strong><?php echo formatPrice($product['price']); ?></strong>
            <span class="stock-info">Tình trạng: <?php echo (int) $product['stock'] > 0 ? 'Còn ' . (int) $product['stock'] . ' sản phẩm' : 'Hết hàng'; ?></span>
        </div>

        <?php if((int) $product['stock'] > 0): ?>
            <form method="POST" class="purchase-box">
                <div class="qty-input">
                    <label>Số lượng:</label>
                    <input type="number" name="quantity" min="1" max="<?php echo (int) $product['stock']; ?>" value="1">
                </div>
                <button class="btn primary full" type="submit">Thêm vào giỏ hàng</button>
            </form>
        <?php else: ?>
            <button class="btn disabled full" disabled>Tạm hết hàng</button>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>