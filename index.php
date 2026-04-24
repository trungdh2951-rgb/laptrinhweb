<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Trang chủ';
$result = $conn->query('SELECT products.*, categories.name AS category_name FROM products LEFT JOIN categories ON products.category_id = categories.id ORDER BY products.id DESC LIMIT 8');
include __DIR__ . '/includes/header.php';
?>
<section class="hero-wrapper">
    <!-- LEFT SIDEBAR: MEGA MENU -->
    <div class="mega-menu-sidebar">
        <!-- Item 1 -->
        <div class="mega-menu-item-wrapper">
            <a href="/Web/products.php?category=1" class="mega-item-link">
                <span><span class="mega-icon">📱</span> Điện thoại, Tablet</span>
                <span>›</span>
            </a>
            <div class="mega-menu-flyout">
                <div class="flyout-grid-main">
                    <div class="flyout-col">
                        <h4>Hãng điện thoại</h4>
                        <div class="brand-grid">
                            <a href="/Web/products.php?category=1&brand=iphone" class="brand-box"><strong>iPhone</strong></a>
                            <a href="/Web/products.php?category=1&brand=samsung" class="brand-box"><strong>SAMSUNG</strong></a>
                            <a href="/Web/products.php?category=1&brand=xiaomi" class="brand-box"><strong>Xiaomi</strong></a>
                            <a href="/Web/products.php?category=1&brand=oppo" class="brand-box"><strong>OPPO</strong></a>
                            <a href="/Web/products.php?category=1&brand=sony" class="brand-box"><strong>SONY</strong></a>
                            <a href="/Web/products.php?category=1&brand=vivo" class="brand-box"><strong>vivo</strong></a>
                        </div>
                        
                        <h4 style="margin-top:20px;">Mức giá điện thoại</h4>
                        <div class="brand-grid">
                            <a href="/Web/products.php?category=1" class="price-box">Dưới 2 triệu</a>
                            <a href="/Web/products.php?category=1" class="price-box">Từ 2 - 7 triệu</a>
                            <a href="/Web/products.php?category=1" class="price-box">Trên 13 triệu</a>
                        </div>
                    </div>
                    
                    <div class="flyout-col">
                        <h4>Điện thoại HOT <span class="hot-icon">⚡</span></h4>
                        <div class="brand-grid" style="grid-template-columns: repeat(2, 1fr);">
                            <a href="/Web/products.php?category=1" class="product-box">iPhone 15 Pro <span class="badge-hot">Hot</span></a>
                            <a href="/Web/products.php?category=1" class="product-box">Galaxy S24 Ultra</a>
                            <a href="/Web/products.php?category=1" class="product-box">Xiaomi 14 Ultra <span class="badge-new">Mới</span></a>
                            <a href="/Web/products.php?category=1" class="product-box">OPPO Find N3</a>
                        </div>
                    </div>

                    <div class="flyout-col">
                        <h4>Hãng máy tính bảng</h4>
                        <div class="brand-grid">
                            <a href="/Web/products.php?category=1" class="brand-box"><strong>iPad</strong></a>
                            <a href="/Web/products.php?category=1" class="brand-box"><strong>SAMSUNG</strong></a>
                            <a href="/Web/products.php?category=1" class="brand-box"><strong>Xiaomi</strong></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item 2 -->
        <div class="mega-menu-item-wrapper">
            <a href="/Web/products.php?category=2" class="mega-item-link">
                <span><span class="mega-icon">💻</span> Laptop</span>
                <span>›</span>
            </a>
            <div class="mega-menu-flyout">
                <div class="flyout-grid-two">
                    <div class="flyout-col">
                        <h4>Thương hiệu</h4>
                        <div class="brand-grid">
                            <a href="/Web/products.php?category=2&brand=MacBook" class="brand-box"><strong>MacBook</strong></a>
                            <a href="/Web/products.php?category=2&brand=ASUS" class="brand-box"><strong>ASUS</strong></a>
                            <a href="/Web/products.php?category=2&brand=Lenovo" class="brand-box"><strong>Lenovo</strong></a>
                            <a href="/Web/products.php?category=2&brand=Dell" class="brand-box"><strong>Dell</strong></a>
                            <a href="/Web/products.php?category=2&brand=HP" class="brand-box"><strong>HP</strong></a>
                            <a href="/Web/products.php?category=2&brand=Acer" class="brand-box"><strong>Acer</strong></a>

                        </div>

                        <h4 style="margin-top:20px;">Thuộc tính cấu hình</h4>
                        <div class="brand-grid">
                            <a href="/Web/products.php?category=2&cpu=Intel%20Core%20i5" class="price-box">CPU Intel Core i5</a>
                            <a href="/Web/products.php?category=2&cpu=Intel%20Core%20i7" class="price-box">CPU Intel Core i7</a>
                            <a href="/Web/products.php?category=2&ram=8GB" class="price-box">RAM 8GB</a>
                            <a href="/Web/products.php?category=2&ram=16GB" class="price-box">RAM 16GB</a>
                        </div>
                        
                        <h4 style="margin-top:20px;">Phân khúc giá</h4>
                        <div class="brand-grid">
                            <a href="/Web/products.php?category=2" class="price-box">Dưới 10 triệu</a>
                            <a href="/Web/products.php?category=2" class="price-box">Từ 10 - 15 tr</a>
                            <a href="/Web/products.php?category=2" class="price-box">Từ 15 - 20 tr</a>
                            <a href="/Web/products.php?category=2" class="price-box">Từ 20 - 25 tr</a>
                            <a href="/Web/products.php?category=2" class="price-box">Trên 25 triệu</a>
                        </div>
                    </div>
                    
                    <div class="flyout-col">
                        <h4>Kích thước màn hình</h4>
                        <div class="brand-grid">
                            <a href="/Web/products.php?category=2&keyword=14%20inch" class="price-box">14 inch</a>
                            <a href="/Web/products.php?category=2&keyword=15.6%20inch" class="price-box">15.6 inch</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mega-menu-item-wrapper"><a href="/Web/products.php?category=4" class="mega-item-link"><span><span class="mega-icon">🎧</span> Âm thanh, mic thu âm</span></a></div>
        <div class="mega-menu-item-wrapper"><a href="/Web/products.php?category=5" class="mega-item-link"><span><span class="mega-icon">⌚</span> Đồng hồ, camera</span></a></div>
        <div class="mega-menu-item-wrapper"><a href="/Web/products.php?category=6" class="mega-item-link"><span><span class="mega-icon">🖥️</span> PC, màn hình, máy in</span></a></div>
        <div class="mega-menu-item-wrapper"><a href="/Web/products.php?category=7" class="mega-item-link"><span><span class="mega-icon">📺</span> Tivi</span></a></div>
        <div class="mega-menu-item-wrapper"><a href="/Web/products.php?category=3" class="mega-item-link"><span><span class="mega-icon">🔌</span> Phụ kiện</span></a></div>
        <div class="mega-menu-item-wrapper"><a href="#" class="mega-item-link"><span><span class="mega-icon">📰</span> Tin tức công nghệ</span></a></div>
    </div>

    <!-- CENTER: SLIDER -->
    <div class="premium-slider-section">
    <div class="slider-wrapper">
        <div class="slider-track" id="mainSliderTrack">
            <div class="slider-slide">
                <img src="/Web/image/690x300_open_iPhone%2017e.webp" alt="iPhone 17e Banner">
            </div>
            <div class="slider-slide">
                <img src="/Web/image/Frame21472288341.webp" alt="Samsung Galaxy S26 Series Banner">
            </div>
            <div class="slider-slide">
                <img src="/Web/image/asus.webp" alt="Asus TUF Gaming Banner">
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
    </div>

    <!-- RIGHT SIDEBAR: PROMO BOX -->
    <div class="right-promo-box">
        <div class="promo-group">
            <div class="promo-group-title">Ưu đãi cho giáo dục</div>
            <a href="#" class="promo-item">
                <span class="icon">🎓</span> <span>Đăng ký <strong>nhận voucher 30/4</strong></span>
            </a>
            <a href="#" class="promo-item">
                <span class="icon">🎓</span> <span>Deal lễ <strong>siêu rẻ cho HSSV</strong></span>
            </a>
            <a href="#" class="promo-item">
                <span class="icon">🎓</span> <span>Laptop <strong>ưu đãi khủng</strong></span>
            </a>
        </div>

        <div class="promo-group">
            <div class="promo-group-title">Thu cũ lên đời giá hời</div>
            <a href="#" class="promo-item">
                <span class="icon">🔄</span> <span>iPhone trợ giá <strong>đến 4 triệu</strong></span>
            </a>
            <a href="#" class="promo-item">
                <span class="icon">🔄</span> <span>Samsung trợ giá <strong>đến 5 triệu</strong></span>
            </a>
            <a href="#" class="promo-item">
                <span class="icon">🔄</span> <span><strong>Đổi máy cũ</strong> lấy máy mới ngay</span>
            </a>
        </div>

        <div class="promo-group">
            <div class="promo-group-title">Khách hàng doanh nghiệp (B2B)</div>
            <a href="#" class="promo-item">
                <span class="icon">💼</span> <span>Đăng ký <strong>Member-UTH</strong></span>
            </a>
            <a href="#" class="promo-item">
                <span class="icon">💼</span> <span>Chính sách <strong>ưu đãi sỉ</strong></span>
            </a>
            <a href="#" class="promo-item">
                <span class="icon">💼</span> <span>Giao hàng <strong>tận nơi 24h</strong></span>
            </a>
        </div>
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