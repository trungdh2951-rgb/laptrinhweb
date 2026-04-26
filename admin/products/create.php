<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/flash.php';

requireAdmin();

$categories = $conn->query('SELECT * FROM categories ORDER BY name ASC');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = (int)($_POST['category_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $cpu = trim($_POST['cpu'] ?? '');
    $ram = trim($_POST['ram'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $image = trim($_POST['image'] ?? '');

    if ($name === '' || $price <= 0) {
        setFlash('error', 'Dữ liệu không hợp lệ!');
    } else {
        $stmt = $conn->prepare('INSERT INTO products (category_id, name, brand, cpu, ram, description, price, stock, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('isssssdis', $categoryId, $name, $brand, $cpu, $ram, $description, $price, $stock, $image);
        $stmt->execute();

        setFlash('success', 'Thêm sản phẩm thành công!');
        header('Location: /Web/admin/products/index.php');
        exit();
    }
}

include __DIR__ . '/../../includes/header.php';
?>
<section class="auth-box">
    <h2>Thêm sản phẩm mới</h2>
    <?php showFlash(); ?>
    
    <form method="POST" class="auth-form wide">
        <label>Danh mục</label>
        <select name="category_id" required>
            <?php while ($cat = $categories->fetch_assoc()): ?>
                <option value="<?php echo $cat['id']; ?>"><?php echo sanitize($cat['name']); ?></option>
            <?php endwhile; ?>
        </select>

        <label>Tên sản phẩm</label>
        <input type="text" name="name" placeholder="Tên sản phẩm" required>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
            <div>
                <label>Hãng (Brand)</label>
                <input type="text" name="brand" placeholder="VD: Apple, Dell...">
            </div>
            <div>
                <label>CPU</label>
                <input type="text" name="cpu" placeholder="VD: i5, i7, M2...">
            </div>
            <div>
                <label>RAM</label>
                <input type="text" name="ram" placeholder="VD: 8GB, 16GB...">
            </div>
        </div>

        <label>Mô tả</label>
        <textarea name="description" placeholder="Mô tả chi tiết sản phẩm"></textarea>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <div>
                <label>Giá</label>
                <input type="number" step="0.01" name="price" placeholder="Giá" required>
            </div>
            <div>
                <label>Tồn kho</label>
                <input type="number" name="stock" placeholder="Số lượng trong kho" required>
            </div>
        </div>

        <label>Tên file ảnh</label>
        <input type="text" name="image" placeholder="VD: iphone13.jpg">

        <button class="btn primary" type="submit">Lưu sản phẩm</button>
    </form>
</section>
