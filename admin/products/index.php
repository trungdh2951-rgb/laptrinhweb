<?php
require_once __DIR__ . '/../../config/database.php';
<<<<<<< HEAD
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/flash.php';

requireAdmin();

$sql = 'SELECT products.*, categories.name AS category_name FROM products LEFT JOIN categories ON products.category_id = categories.id ORDER BY products.id DESC';
$items = $conn->query($sql);

include __DIR__ . '/../../includes/header.php';
?>
<section class="section-headline">
    <div>
        <h2>Quản lý sản phẩm</h2>
        <p>Danh sách tất cả các sản phẩm trong hệ thống.</p>
    </div>
    <a class="btn primary" href="/Web/admin/products/create.php">Thêm sản phẩm</a>
</section>

<?php showFlash(); ?>

<div class="cart-table-wrap" style="margin-top: 20px;">
    <table class="cart-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ảnh</th>
                <th>Tên / Hãng</th>
                <th>Thông số (CPU/RAM)</th>
                <th>Danh mục</th>
                <th>Giá</th>
                <th>Kho</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $items->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td>
                        <?php if($row['image']): ?>
                            <img src="/Web/image/<?php echo sanitize($row['image']); ?>" width="50" style="border-radius:4px;">
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?php echo sanitize($row['name']); ?></strong><br>
                        <small style="color: #666;"><?php echo sanitize($row['brand'] ?? 'N/A'); ?></small>
                    </td>
                    <td>
                        <small>
                            CPU: <?php echo sanitize($row['cpu'] ?? '-'); ?><br>
                            RAM: <?php echo sanitize($row['ram'] ?? '-'); ?>
                        </small>
                    </td>
                    <td><?php echo sanitize($row['category_name'] ?? 'Chưa phân loại'); ?></td>
                    <td><strong><?php echo formatPrice($row['price']); ?></strong></td>
                    <td><?php echo $row['stock']; ?></td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a class="btn small" href="/Web/admin/products/edit.php?id=<?php echo $row['id']; ?>">Sửa</a>
                            <a class="btn small danger" href="/Web/admin/products/delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Xóa sản phẩm này?')">Xóa</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
