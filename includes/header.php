<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? 'UTH Shop';
$baseUrl = '/Web';
$currentUser = $_SESSION['user'] ?? null;
$cartCount = 0;
foreach ($_SESSION['cart'] ?? [] as $item) {
    $cartCount += (int) ($item['quantity'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitize($pageTitle); ?></title>
    <!-- Nhúng Font Inter để hiển thị tiếng Việt chuẩn nhất -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/style.css?v=<?php echo time(); ?>">
</head>

<body>
    <header class="site-header">
        <div class="top-strip">
            <div class="container top-strip-inner">
                <span>Chính hãng 100%</span>
                <span>Giao nhanh trong ngày</span>
                <span>Hỗ trợ đặt hàng dễ dàng</span>
            </div>
        </div>

        <div class="main-header">
            <div class="container main-header-inner">
                <a class="brand logo-image-wrap" href="<?php echo $baseUrl; ?>/index.php">
                   <img src="<?php echo $baseUrl; ?>/image/logo.jpg" alt="UTH Shop Logo" class="site-logo">
               </a>

                <form class="search-bar" action="<?php echo $baseUrl; ?>/products.php" method="GET">
                    <input type="text" name="keyword" placeholder="Tìm kiếm sản phẩm...">
                    <button type="submit">Tìm</button>
                </form>

                <div class="header-actions">
                    <a href="<?php echo $baseUrl; ?>/products.php">Sản phẩm</a>
                        <?php if ($currentUser): ?>
                        <a href="<?php echo $baseUrl; ?>/orders.php">Đơn hàng</a>
                            <?php if ($currentUser['role'] === 'admin'): ?>
                            <a href="<?php echo $baseUrl; ?>/admin/index.php">Quản trị</a>
                            <?php endif; ?>
                        <a href="<?php echo $baseUrl; ?>/logout.php">Đăng xuất</a>
                        <?php else: ?>
                        <a href="<?php echo $baseUrl; ?>/login.php">Đăng nhập</a>
                        <a href="<?php echo $baseUrl; ?>/register.php">Đăng ký</a>
                        <?php endif; ?>
                    <a class="cart-link" href="<?php echo $baseUrl; ?>/cart.php">Giỏ hàng
                        <span><?php echo $cartCount; ?></span></a>
                </div>
            </div>
        </div>

        <div class="category-bar">
            <div class="container category-bar-inner">
                <a href="<?php echo $baseUrl; ?>/index.php">Trang chủ</a>
                <a href="<?php echo $baseUrl; ?>/products.php?category=1">Điện thoại</a>
                <a href="<?php echo $baseUrl; ?>/products.php?category=2">Laptop</a>
                <a href="<?php echo $baseUrl; ?>/products.php?category=3">Phụ kiện</a>
                <a href="<?php echo $baseUrl; ?>/register.php">Đăng ký thành viên</a>
            </div>
        </div>
    </header>
    <main class="container page-content">