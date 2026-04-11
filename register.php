<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('INSERT INTO users (full_name, email, password, phone, address, role) VALUES (?, ?, ?, ?, ?, "user")');
    $stmt->bind_param('sssss', $fullName, $email, $hashedPassword, $phone, $address);

    if ($stmt->execute()) {
        $success = 'Đăng ký thành công. Bạn có thể đăng nhập ngay.';
    } else {
        $error = 'Email đã tồn tại hoặc dữ liệu chưa hợp lệ.';
    }
}

$pageTitle = 'Đăng ký';
include __DIR__ . '/includes/header.php';
?>
<section class="auth-layout">
    <div class="auth-side auth-side-register">
        <span class="section-kicker">Thành viên mới</span>
        <h2>Tạo tài khoản để bắt đầu</h2>
        <p>Đăng ký nhanh để lưu đơn hàng, quản lý thông tin cá nhân và trải nghiệm giao diện bán hàng hoàn chỉnh hơn.
        </p>
        <ul class="auth-points">
            <li>Đăng ký chỉ mất chưa tới 1 phút</li>
            <li>Lưu giỏ hàng và lịch sử đơn</li>
            <li>Dễ nâng cấp vai trò admin trong phpMyAdmin khi cần demo</li>
        </ul>
    </div>

    <div class="auth-box premium-auth-box">
        <h2>Tạo tài khoản</h2>
        <?php if ($error): ?>
            <div class="alert error"><?php echo sanitize($error); ?></div><?php endif; ?>
        <?php if ($success): ?>
            <div class="alert success"><?php echo sanitize($success); ?></div><?php endif; ?>

        <form method="POST" class="auth-form premium-auth-form">
            <input type="text" name="full_name" placeholder="Họ và tên" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <input type="text" name="phone" placeholder="Số điện thoại">
            <textarea name="address" placeholder="Địa chỉ"></textarea>
            <button class="btn primary full" type="submit">Đăng ký tài khoản</button>
        </form>

        <p class="helper-text">Đã có tài khoản? <a class="text-link" href="/Web/login.php">Đăng nhập</a></p>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>