<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Sản phẩm';
$keyword = trim($_GET['keyword'] ?? '');
$categoryId = (int) ($_GET['category'] ?? 0);
$categories = $conn->query('SELECT * FROM categories ORDER BY name ASC');

$sql = 'SELECT products.*, categories.name AS category_name FROM products LEFT JOIN categories ON products.category_id = categories.id WHERE 1';
$params = [];
$types = '';

if ($keyword !== '') {
    $sql .= ' AND products.name LIKE ?';
    $params[] = '%' . $keyword . '%';
    $types .= 's';
}

if ($categoryId > 0) {
    $sql .= ' AND products.category_id = ?';
    $params[] = $categoryId;
    $types .= 'i';
}

$sql .= ' ORDER BY products.id DESC';
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

include __DIR__ . '/includes/header.php';
?>
<section class="listing-hero">
    <div>
        <span class="section-kicker">Kho sản phẩm</span>
        <h1>Chọn sản phẩm phù hợp với nhu cầu của bạn</h1>
        <p>Tìm kiếm nhanh theo tên hoặc lọc theo danh mục để xem các mặt hàng nổi bật trong cửa hàng demo.</p>
    </div>
</section>

<form class="filter-bar elevated premium-filter" method="GET">
    <input type="text" name="keyword" placeholder="Nhập tên sản phẩm..." value="<?php echo sanitize($keyword); ?>">
    <select name="category">
        <option value="0">Tất cả danh mục</option>
        <?php while ($cat = $categories->fetch_assoc()): ?>
            <option value="<?php echo $cat['id']; ?>" <?php echo $categoryId === (int) $cat['id'] ? 'selected' : ''; ?>>
                <?php echo sanitize($cat['name']); ?>
            </option>
        <?php endwhile; ?>
    </select>
    <button class="btn primary" type="submit">Lọc sản phẩm</button>
</form>

<div class="product-grid premium-grid">
    <?php while ($row = $result->fetch_assoc()): ?>
        <article class="product-card shop-card premium-card">
            <div class="product-thumb">
                <span class="thumb-label">New</span>
                <?php if(!empty($row['image'])): ?>
                    <img src="/Web/image/<?php echo sanitize($row['image']); ?>" alt="<?php echo sanitize($row['name']); ?>" class="product-entry-img">
                <?php else: ?>
                    <?php echo sanitize($row['name']); ?>
                <?php endif; ?>
            </div>
            <div class="product-card-body">
                <span class="badge"><?php echo sanitize($row['category_name'] ?? 'Chưa phân loại'); ?></span>
                <h3><?php echo sanitize($row['name']); ?></h3>
                <p><?php echo sanitize(mb_strimwidth($row['description'] ?? '', 0, 100, '...')); ?></p>

                <div class="price-stack">
                    <strong><?php echo formatPrice($row['price']); ?></strong>
                    <span class="old-price">Sẵn hàng: <?php echo (int) $row['stock']; ?></span>
                </div>

                <div class="product-meta vertical">
                    <a class="btn primary small full" href="/Web/product_detail.php?id=<?php echo $row['id']; ?>">Chi tiết
                        sản phẩm</a>
                    <a class="btn light small full" href="/Web/cart.php">Đi đến giỏ hàng</a>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>