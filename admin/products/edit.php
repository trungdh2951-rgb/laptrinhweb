<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/flash.php';

requireAdmin();

$id = (int)($_GET['id'] ?? 0);
$itemStmt = $conn->prepare('SELECT * FROM products WHERE id = ?');
$itemStmt->bind_param('i', $id);
$itemStmt->execute();
$item = $itemStmt->get_result()->fetch_assoc();

if (!$item) {
    die('Không tìm thấy sản phẩm.');
}

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
        $stmt = $conn->prepare('UPDATE products SET category_id = ?, name = ?, brand = ?, cpu = ?, ram = ?, description = ?, price = ?, stock = ?, image = ? WHERE id = ?');
        $stmt->bind_param('isssssdisi', $categoryId, $name, $brand, $cpu, $ram, $description, $price, $stock, $image, $id);
        $stmt->execute();

        setFlash('success', 'Cập nhật thành công!');
        header('Location: /Web/admin/products/index.php');
        exit();
    }
}

include __DIR__ . '/../../includes/header.php';
?>
<section class="auth-box">
    <h2>Sửa sản phẩm</h2>
    <?php showFlash(); ?>
    
    <form method="POST" class="auth-form wide" autocomplete="off">
        <label>Danh mục</label>
        <select name="category_id" required>
            <?php while ($cat = $categories->fetch_assoc()): ?>
                <option value="<?php echo $cat['id']; ?>" <?php echo (int)$item['category_id'] === (int)$cat['id'] ? 'selected' : ''; ?>>
                    <?php echo sanitize($cat['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Tên sản phẩm</label>
        <input type="text" name="name" value="<?php echo sanitize($item['name']); ?>" required>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
            <div>
                <label>Hãng (Brand)</label>
                <input type="text" name="brand" value="<?php echo sanitize($item['brand'] ?? ''); ?>" placeholder="VD: Apple, Dell...">
            </div>
            <div>
                <label>CPU</label>
                <input type="text" name="cpu" value="<?php echo sanitize($item['cpu'] ?? ''); ?>" placeholder="VD: i5, i7, M2...">
            </div>
            <div>
                <label>RAM</label>
                <input type="text" name="ram" value="<?php echo sanitize($item['ram'] ?? ''); ?>" placeholder="VD: 8GB, 16GB...">
            </div>
        </div>

        <label>Mô tả</label>
        <textarea name="description"><?php echo sanitize($item['description']); ?></textarea>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <div>
                <label>Giá</label>
                <input type="number" step="0.01" name="price" value="<?php echo $item['price']; ?>" required>
            </div>
            <div>
                <label>Tồn kho</label>
                <input type="number" name="stock" value="<?php echo $item['stock']; ?>" required>
            </div>
        </div>

        <label>Tên file ảnh</label>
        <input type="text" name="image" value="<?php echo sanitize($item['image']); ?>">

        <button class="btn primary" type="submit">Cập nhật sản phẩm</button>
    </form>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>