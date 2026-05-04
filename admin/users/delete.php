<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/flash.php';

requireAdmin();

$id = (int)($_GET['id'] ?? 0);
$currentUserId = (int)($_SESSION['user']['id'] ?? 0);

if ($id === $currentUserId) {
    setFlash('error', 'Bạn không thể xóa tài khoản đang đăng nhập.');
    header('Location: /Web/admin/users/index.php');
    exit();
}

$countAdminResult = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'admin'");
$adminCount = (int)($countAdminResult->fetch_assoc()['total'] ?? 0);

$userStmt = $conn->prepare('SELECT role FROM users WHERE id = ? LIMIT 1');
$userStmt->bind_param('i', $id);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();

if (!$user) {
    setFlash('error', 'Không tìm thấy người dùng cần xóa.');
    header('Location: /Web/admin/users/index.php');
    exit();
}

if ($user['role'] === 'admin' && $adminCount <= 1) {
    setFlash('error', 'Không thể xóa admin cuối cùng của hệ thống.');
    header('Location: /Web/admin/users/index.php');
    exit();
}

$stmt = $conn->prepare('DELETE FROM users WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();

setFlash('success', 'Đã xóa người dùng thành công.');
header('Location: /Web/admin/users/index.php');
exit();
?>
