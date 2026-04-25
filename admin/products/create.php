<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/flash.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];

    if ($name === '' || $price <= 0) {
        setFlash('error', 'Dữ liệu không hợp lệ!');
    } else {
        $stmt = $conn->prepare("INSERT INTO products(name, price, stock) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $name, $price, $stock);
        $stmt->execute();

        setFlash('success', 'Thêm sản phẩm thành công!');
        header("Location: index.php");
        exit();
    }
}


include __DIR__ . '/../../includes/header.php';
?>

<h2>Thêm sản phẩm</h2>

<?php showFlash(); ?>

<form method="POST" class="auth-form">
    <input name="name" placeholder="Tên" required>
    <input name="price" type="number" placeholder="Giá" required>
    <input name="stock" type="number" placeholder="Stock" required>
    <button class="btn primary">Thêm</button>
</form>

<?php include __DIR__ . '/../../includes/footer.php'; ?>