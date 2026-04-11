<?php
require_once __DIR__ . '/../../config/database.php'; require_once __DIR__ . '/../../includes/functions.php'; require_once __DIR__ . '/../../includes/auth.php'; requireAdmin(); $sql = 'SELECT products.*, categories.name AS category_name FROM products LEFT JOIN categories ON products.category_id = categories.id ORDER BY products.id DESC'; $items = $conn->query($sql); include __DIR__ . '/../../includes/header.php';
?>
<section class="section-title"><h2>Quản lý sản phẩm</h2></section>
<a class="btn primary" href="/Web/admin/products/create.php">Thêm sản phẩm</a>
<div class="cart-table-wrap"><table class="cart-table"><thead><tr><th>ID</th><th>Tên</th><th>Danh mục</th><th>Giá</th><th>Tồn kho</th><th>Thao tác</th></tr></thead><tbody>
<?php while ($row = $items->fetch_assoc()): ?>
<tr><td><?php echo $row['id']; ?></td><td><?php echo sanitize($row['name']); ?></td><td><?php echo sanitize($row['category_name'] ?? ''); ?></td><td><?php echo formatPrice($row['price']); ?></td><td><?php echo $row['stock']; ?></td><td><a class="btn small" href="/Web/admin/products/edit.php?id=<?php echo $row['id']; ?>">Sửa</a> <a class="btn small danger" href="/Web/admin/products/delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Xóa sản phẩm này?')">Xóa</a></td></tr>
<?php endwhile; ?>
</tbody></table></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
