<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Trang chủ';
$result = $conn->query('SELECT products.*, categories.name AS category_name FROM products LEFT JOIN categories ON products.category_id = categories.id ORDER BY products.id DESC LIMIT 8');
include __DIR__ . '/includes/header.php';
?>
<section class="premium-slider-section">
    <div class="slider-wrapper">
        <div class="slider-track" id="mainSliderTrack">
            <div class="slider-slide">
                <img src="/Web/image/690x300_open_iPhone%2017e.webp" alt="iPhone 17e Banner">
            </div>
            <div class="slider-slide">
                <img src="/Web/image/Frame21472288341.webp" alt="Samsung Galaxy S26 Series Banner">
            </div>
            <div class="slider-slide">
                <img src="/Web/image/banner_apple.png" alt="Apple Ecosystem">
            </div>
        </div>
        
        <button class="slider-btn prev" id="sliderPrevBtn">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <button class="slider-btn next" id="sliderNextBtn">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
        </button>

        <div class="slider-dots-container" id="sliderDots"></div>
    </div>
</section>

<section class="quick-categories">
    <a class="quick-category fancy" href="/Web/products.php?category=1">
        <span class="quick-icon">📱</span>
        <div>
            <strong>Điện thoại</strong>
            <small>Nhiều mẫu nổi bật</small>
        </div>
    </a>
    <a class="quick-category fancy" href="/Web/products.php?category=2">
        <span class="quick-icon">💻</span>
        <div>
            <strong>Laptop</strong>
            <small>Học tập, làm việc</small>
        </div>
    </a>
    <a class="quick-category fancy" href="/Web/products.php?category=3">
        <span class="quick-icon">🎧</span>
        <div>
            <strong>Phụ kiện</strong>
            <small>Gọn nhẹ, tiện dụng</small>
        </div>
    </a>
    <a class="quick-category fancy" href="/Web/cart.php">
        <span class="quick-icon">🛒</span>
        <div>
            <strong>Giỏ hàng</strong>
            <small>Xem đơn nhanh</small>
        </div>
    </a>
</section>

<section class="section-headline">
    <div>
        <span class="section-kicker">Sản phẩm nổi bật</span>
        <h2>Gợi ý dành cho bạn</h2>
    </div>
    <a class="text-link" href="/Web/products.php">Xem tất cả</a>
</section>

<section class="product-grid premium-grid">
    <?php while ($row = $result->fetch_assoc()): ?>
        <article class="product-card shop-card premium-card">
            <div class="product-thumb">
                <span class="thumb-label">Hot</span>
                <?php if(!empty($row['image'])): ?>
                    <img src="/Web/image/<?php echo sanitize($row['image']); ?>" alt="<?php echo sanitize($row['name']); ?>" class="product-entry-img">
                <?php else: ?>
                    <?php echo sanitize($row['name']); ?>
                <?php endif; ?>
            </div>
            <div class="product-card-body">
                <span class="badge"><?php echo sanitize($row['category_name'] ?? 'Chưa phân loại'); ?></span>
                <h3><?php echo sanitize($row['name']); ?></h3>
                <p><?php echo sanitize(mb_strimwidth($row['description'] ?? '', 0, 90, '...')); ?></p>

                <div class="price-stack">
                    <strong><?php echo formatPrice($row['price']); ?></strong>
                    <span class="old-price">Ưu đãi giá hời!!</span>
                </div>

                <div class="product-meta vertical">
                    <a class="btn primary small full" href="/Web/product_detail.php?id=<?php echo $row['id']; ?>">Xem chi
                        tiết</a>
                    <span class="stock-label">Còn <?php echo (int) $row['stock']; ?> sản phẩm</span>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>