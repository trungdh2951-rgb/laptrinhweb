<?php
session_start(); // 👈 THÊM DÒNG NÀY

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header('Location: /Web/index.php');
        exit();
    }

    $error = 'Email hoặc mật khẩu không đúng.';
}

$pageTitle = 'Đăng nhập';
include __DIR__ . '/includes/header.php';
?>
<section class="auth-layout">
    <div class="auth-side auth-side-login">
        <span class="section-kicker">Chào mừng quay lại</span>
        <h2>Đăng nhập để tiếp tục mua sắm</h2>
        <p>Quản lý đơn hàng, giỏ hàng và tài khoản của bạn trong giao diện mới gọn gàng hơn.</p>
        <ul class="auth-points">
            <li>Theo dõi đơn hàng</li>
            <li>Lưu thông tin người dùng</li>
            <li>Truy cập nhanh khu vực quản trị nếu là admin</li>
        </ul>
    </div>

    <div class="auth-box premium-auth-box">
        <h2>Đăng nhập</h2>
        <?php if ($error): ?>
            <div class="alert error"><?php echo sanitize($error); ?></div><?php endif; ?>

        <form method="POST" class="auth-form premium-auth-form">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button class="btn primary full" type="submit">Đăng nhập ngay</button>
        </form>

        <p class="helper-text">Tài khoản mẫu ghi trong README có thể không khớp nếu dữ liệu cũ đã bị thay đổi.</p>
        <p class="helper-text">Chưa có tài khoản? <a class="text-link" href="/Web/register.php">Đăng ký tại đây</a></p>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>