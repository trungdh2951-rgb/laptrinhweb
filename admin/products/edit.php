include __DIR__ . '/../../includes/header.php';
<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/flash.php';

requireAdmin();

$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) die("Không tồn tại");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];

    if ($name === '' || $price <= 0) {
        setFlash('error', 'Dữ liệu không hợp lệ!');
    } else {
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, stock=? WHERE id=?");
        $stmt->bind_param("sdii", $name, $price, $stock, $id);
        $stmt->execute();

        setFlash('success', 'Cập nhật thành công!');
        header("Location: index.php");
        exit();
    }
}

include __DIR__ . '/../../includes/header.php';
?>

<h2>Sửa sản phẩm</h2>
<?php showFlash(); ?>

<form method="POST" class="auth-form">
    <input name="name" value="<?= sanitize($product['name']) ?>">
    <input name="price" type="number" value="<?= $product['price'] ?>">
    <input name="stock" type="number" value="<?= $product['stock'] ?>">
    <button class="btn primary">Cập nhật</button>
</form>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
include __DIR__ . '/../../includes/footer.php';