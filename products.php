<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Sản phẩm';
$keyword = trim($_GET['keyword'] ?? '');
$categoryId = (int) ($_GET['category'] ?? 0);
$brand = trim($_GET['brand'] ?? '');
$cpu = trim($_GET['cpu'] ?? '');
$ram = trim($_GET['ram'] ?? '');
$priceMin = (int) ($_GET['price_min'] ?? 0);
$priceMax = (int) ($_GET['price_max'] ?? 0);

// Chuẩn hoá tham số để tránh lệch dữ liệu giữa menu và DB
if (strcasecmp($brand, 'MacBook') === 0) {
    $brand = 'Apple';
}
$cpu = str_ireplace(['Intel Core ', 'Intel '], '', $cpu);

$categories = $conn->query('SELECT * FROM categories ORDER BY name ASC');

// Lấy danh sách hãng, cpu, ram duy nhất từ DB để làm bộ lọc
$brands = $conn->query('SELECT DISTINCT brand FROM products WHERE brand IS NOT NULL AND brand != "" ORDER BY brand ASC');
$cpus = $conn->query('SELECT DISTINCT cpu FROM products WHERE cpu IS NOT NULL AND cpu != "" ORDER BY cpu ASC');
$rams = $conn->query('SELECT DISTINCT ram FROM products WHERE ram IS NOT NULL AND ram != "" ORDER BY ram ASC');

$sql = 'SELECT products.*, categories.name AS category_name FROM products LEFT JOIN categories ON products.category_id = categories.id WHERE 1';
$params = [];
$types = '';

if ($keyword !== '') {
    $sql .= ' AND (products.name LIKE ? OR products.description LIKE ?)';
    $params[] = '%' . $keyword . '%';
    $params[] = '%' . $keyword . '%';
    $types .= 'ss';
}

if ($categoryId > 0) {
    $sql .= ' AND products.category_id = ?';
    $params[] = $categoryId;
    $types .= 'i';
}

if ($brand !== '') {
    $sql .= ' AND products.brand LIKE ?';
    $params[] = '%' . $brand . '%';
    $types .= 's';
}

if ($cpu !== '') {
    $sql .= ' AND products.cpu LIKE ?';
    $params[] = '%' . $cpu . '%';
    $types .= 's';
}

if ($ram !== '') {
    $sql .= ' AND products.ram LIKE ?';
    $params[] = '%' . $ram . '%';
    $types .= 's';
}

if ($priceMin > 0) {
    $sql .= ' AND products.price >= ?';
    $params[] = $priceMin;
    $types .= 'i';
}

if ($priceMax > 0) {
    $sql .= ' AND products.price <= ?';
    $params[] = $priceMax;
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
        <p>Tìm kiếm nhanh theo tên hoặc lọc theo các thuộc tính kỹ thuật của Laptop.</p>
    </div>
</section>

<form class="filter-bar elevated premium-filter" method="GET" style="flex-wrap: wrap; gap: 10px;">
    <input type="text" name="keyword" placeholder="Nhập tên sản phẩm..." value="<?php echo sanitize($keyword); ?>" style="flex: 1 1 200px;">
    
    <select name="category">
        <option value="0">Tất cả danh mục</option>
        <?php while ($cat = $categories->fetch_assoc()): ?>
            <option value="<?php echo $cat['id']; ?>" <?php echo $categoryId === (int) $cat['id'] ? 'selected' : ''; ?>>
                <?php echo sanitize($cat['name']); ?>
            </option>
        <?php endwhile; ?>
    </select>

    <select name="brand">
        <option value="">Tất cả hãng</option>
        <?php while ($b = $brands->fetch_assoc()): ?>
            <option value="<?php echo sanitize($b['brand']); ?>" <?php echo $brand === $b['brand'] ? 'selected' : ''; ?>>
                <?php echo sanitize($b['brand']); ?>
            </option>
        <?php endwhile; ?>
    </select>

    <select name="cpu">
        <option value="">Tất cả CPU</option>
        <?php while ($c = $cpus->fetch_assoc()): ?>
            <option value="<?php echo sanitize($c['cpu']); ?>" <?php echo $cpu === $c['cpu'] ? 'selected' : ''; ?>>
                <?php echo sanitize($c['cpu']); ?>
            </option>
        <?php endwhile; ?>
    </select>

    <select name="ram">
        <option value="">Tất cả RAM</option>
        <?php while ($r = $rams->fetch_assoc()): ?>
            <option value="<?php echo sanitize($r['ram']); ?>" <?php echo $ram === $r['ram'] ? 'selected' : ''; ?>>
                <?php echo sanitize($r['ram']); ?>
            </option>
        <?php endwhile; ?>
    </select>

    <input type="number" name="price_min" placeholder="Giá từ" value="<?php echo $priceMin > 0 ? $priceMin : ''; ?>" style="width:120px;">
    <input type="number" name="price_max" placeholder="Giá đến" value="<?php echo $priceMax > 0 ? $priceMax : ''; ?>" style="width:120px;">

    <button class="btn primary" type="submit">Lọc sản phẩm</button>
    <a href="products.php" class="btn light">Xóa lọc</a>
</form>

<div class="product-grid premium-grid">
    <?php
    $hasProducts = false;
    while ($row = $result->fetch_assoc()):
        $hasProducts = true;
    ?>
        <article class="product-card shop-card premium-card">
            <div class="product-thumb">
                <span class="thumb-label">Mới</span>
                <?php if(!empty($row['image'])): ?>
                    <img src="/Web/image/<?php echo sanitize($row['image']); ?>" alt="<?php echo sanitize($row['name']); ?>" class="product-entry-img">
                <?php else: ?>
                    <div class="no-image-placeholder"><?php echo sanitize($row['name']); ?></div>
                <?php endif; ?>
            </div>
            <div class="product-card-body">
                <div style="display: flex; gap: 5px; margin-bottom: 5px;">
                    <span class="badge"><?php echo sanitize($row['category_name'] ?? 'Chưa phân loại'); ?></span>
                    <?php if($row['brand']): ?>
                        <span class="badge light"><?php echo sanitize($row['brand']); ?></span>
                    <?php endif; ?>
                </div>
                <h3><?php echo sanitize($row['name']); ?></h3>
                
                <!-- Hiển thị thuộc tính kỹ thuật nếu có -->
                <div class="tech-specs" style="margin: 8px 0; font-size: 0.85em; color: #666; display: flex; gap: 10px;">
                    <?php if($row['cpu']): ?> <span>💻 <?php echo sanitize($row['cpu']); ?></span> <?php endif; ?>
                    <?php if($row['ram']): ?> <span>💾 <?php echo sanitize($row['ram']); ?></span> <?php endif; ?>
                </div>

                <p style="height: 40px; overflow: hidden;"><?php echo sanitize(mb_strimwidth($row['description'] ?? '', 0, 80, '...')); ?></p>

                <div class="price-stack">
                    <strong><?php echo formatPrice($row['price']); ?></strong>
                    <span class="old-price">Kho: <?php echo (int) $row['stock']; ?></span>
                </div>

                <div class="product-meta vertical">
                    <a class="btn primary small full" href="/Web/product_detail.php?id=<?php echo $row['id']; ?>">Chi tiết</a>
                    <form method="POST" action="/Web/product_detail.php?id=<?php echo $row['id']; ?>" style="width: 100%;">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn light small full">Thêm vào giỏ</button>
                    </form>
                </div>
            </div>
        </article>
    <?php endwhile; ?>

    <?php if (!$hasProducts): ?>
        <div class="empty-state" style="grid-column: 1 / -1; padding: 40px; text-align:center; background:#fff; border-radius:12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <p>Không tìm thấy sản phẩm nào phù hợp với bộ lọc.</p>
            <a href="products.php" class="btn primary">Xem tất cả sản phẩm</a>
        </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>