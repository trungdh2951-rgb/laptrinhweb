<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/flash.php';


requireAdmin();

$result = $conn->query("SELECT * FROM products ORDER BY id DESC");

include __DIR__ . '/../../includes/header.php';
?>

<section class="section-title">
    <h2>Quản lý sản phẩm</h2>
</section>
<?php showFlash(); ?>

<a class="btn primary" href="create.php">➕ Thêm sản phẩm</a>

<div class="cart-table-wrap">
<table class="cart-table">
<thead>
<tr>
    <th>ID</th>
    <th>Tên</th>
    <th>Giá</th>
    <th>Stock</th>
    <th>Thao tác</th>
</tr>
</thead>

<tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= sanitize($row['name']) ?></td>
    <td><?= number_format($row['price']) ?> đ</td>
    <td><?= $row['stock'] ?></td>
    <td>
        <a class="btn small" href="edit.php?id=<?= $row['id'] ?>">Sửa</a>
        <a class="btn small danger"
           href="delete.php?id=<?= $row['id'] ?>"
           onclick="return confirm('Xóa sản phẩm này?')">Xóa</a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
