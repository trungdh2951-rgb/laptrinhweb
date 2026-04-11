<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();
$pageTitle = 'Quản trị';
$totalProducts = $conn->query('SELECT COUNT(*) AS total FROM products')->fetch_assoc()['total'] ?? 0;
$totalUsers = $conn->query('SELECT COUNT(*) AS total FROM users')->fetch_assoc()['total'] ?? 0;
$totalOrders = $conn->query('SELECT COUNT(*) AS total FROM orders')->fetch_assoc()['total'] ?? 0;
include __DIR__ . '/../includes/header.php';
?>
<section class="section-title"><h2>Dashboard quản trị</h2></section>
<div class="stats-grid">
    <div class="stat-box"><span>Sản phẩm</span><strong><?php echo $totalProducts; ?></strong></div>
    <div class="stat-box"><span>Người dùng</span><strong><?php echo $totalUsers; ?></strong></div>
    <div class="stat-box"><span>Đơn hàng</span><strong><?php echo $totalOrders; ?></strong></div>
</div>
<div class="admin-links">
    <a class="btn" href="/Web/admin/categories/index.php">Quản lý danh mục</a>
    <a class="btn" href="/Web/admin/products/index.php">Quản lý sản phẩm</a>
    <a class="btn" href="/Web/admin/users/index.php">Quản lý người dùng</a>
    <a class="btn" href="/Web/admin/orders/index.php">Quản lý đơn hàng</a>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
