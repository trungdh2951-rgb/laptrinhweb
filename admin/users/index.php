<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/flash.php';

requireAdmin();

$keyword = trim($_GET['keyword'] ?? '');
$role = trim($_GET['role'] ?? '');
$dateFrom = trim($_GET['date_from'] ?? '');
$dateTo = trim($_GET['date_to'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_role') {
    $userId = (int)($_POST['user_id'] ?? 0);
    $newRole = trim($_POST['new_role'] ?? '');
    $currentUserId = (int)($_SESSION['user']['id'] ?? 0);

    if (!in_array($newRole, ['admin', 'user'], true)) {
        setFlash('error', 'Vai trò không hợp lệ.');
    } elseif ($userId === $currentUserId && $newRole !== 'admin') {
        setFlash('error', 'Bạn không thể hạ quyền tài khoản admin đang đăng nhập.');
    } else {
        $countAdminResult = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'admin'");
        $adminCount = (int)($countAdminResult->fetch_assoc()['total'] ?? 0);

        $currentRoleStmt = $conn->prepare('SELECT role FROM users WHERE id = ? LIMIT 1');
        $currentRoleStmt->bind_param('i', $userId);
        $currentRoleStmt->execute();
        $targetUser = $currentRoleStmt->get_result()->fetch_assoc();

        if (!$targetUser) {
            setFlash('error', 'Không tìm thấy người dùng cần phân quyền.');
        } elseif ($targetUser['role'] === 'admin' && $newRole === 'user' && $adminCount <= 1) {
            setFlash('error', 'Không thể hạ quyền admin cuối cùng của hệ thống.');
        } else {
            $updateRole = $conn->prepare('UPDATE users SET role = ? WHERE id = ?');
            $updateRole->bind_param('si', $newRole, $userId);
            $updateRole->execute();
            setFlash('success', 'Cập nhật vai trò người dùng thành công.');
        }
    }

    $query = $_GET ? ('?' . http_build_query($_GET)) : '';
    header('Location: /Web/admin/users/index.php' . $query);
    exit();
}

$sql = 'SELECT id, full_name, email, phone, role, created_at FROM users WHERE 1';
$params = [];
$types = '';

if ($keyword !== '') {
    $sql .= ' AND (full_name LIKE ? OR email LIKE ? OR phone LIKE ?)';
    $like = '%' . $keyword . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= 'sss';
}

if ($role !== '' && in_array($role, ['admin', 'user'], true)) {
    $sql .= ' AND role = ?';
    $params[] = $role;
    $types .= 's';
}

if ($dateFrom !== '') {
    $sql .= ' AND created_at >= ?';
    $params[] = $dateFrom . ' 00:00:00';
    $types .= 's';
}

if ($dateTo !== '') {
    $sql .= ' AND created_at <= ?';
    $params[] = $dateTo . ' 23:59:59';
    $types .= 's';
}

$sql .= ' ORDER BY id DESC';
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$items = $stmt->get_result();
$totalUsers = $items->num_rows;

include __DIR__ . '/../../includes/header.php';
?>
<section class="section-headline admin-page-head">
    <div>
        <span class="section-kicker">Khu vực quản trị</span>
        <h2>Quản lý người dùng</h2>
        <p>Tìm kiếm, lọc, kiểm soát tài khoản và phân quyền người dùng trong hệ thống.</p>
    </div>
    <div class="admin-count-pill"><?php echo $totalUsers; ?> kết quả</div>
</section>

<?php showFlash(); ?>

<form method="GET" class="admin-filter-panel user-filter-panel">
    <div class="filter-field filter-field-wide">
        <label for="keyword">Tìm kiếm</label>
        <input id="keyword" type="text" name="keyword" placeholder="Nhập họ tên, email hoặc số điện thoại..." value="<?php echo sanitize($keyword); ?>">
    </div>

    <div class="filter-field">
        <label for="role">Vai trò</label>
        <select id="role" name="role">
            <option value="">Tất cả vai trò</option>
            <option value="admin" <?php echo $role === 'admin' ? 'selected' : ''; ?>>Admin</option>
            <option value="user" <?php echo $role === 'user' ? 'selected' : ''; ?>>User</option>
        </select>
    </div>

    <div class="filter-field">
        <label for="date_from">Từ ngày</label>
        <input id="date_from" type="date" name="date_from" value="<?php echo sanitize($dateFrom); ?>">
    </div>

    <div class="filter-field">
        <label for="date_to">Đến ngày</label>
        <input id="date_to" type="date" name="date_to" value="<?php echo sanitize($dateTo); ?>">
    </div>

    <div class="filter-actions">
        <button class="btn primary" type="submit">Lọc người dùng</button>
        <a class="btn light" href="/Web/admin/users/index.php">Xóa lọc</a>
    </div>
</form>

<div class="cart-table-wrap admin-table-card">
    <?php if ($totalUsers > 0): ?>
        <table class="cart-table admin-users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Vai trò</th>
                    <th>Phân quyền</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $items->fetch_assoc()): ?>
                    <tr>
                        <td data-label="ID"><?php echo $row['id']; ?></td>
                        <td data-label="Họ tên"><strong><?php echo sanitize($row['full_name']); ?></strong></td>
                        <td data-label="Email"><?php echo sanitize($row['email']); ?></td>
                        <td data-label="SĐT"><?php echo sanitize($row['phone']); ?></td>
                        <td data-label="Vai trò"><span class="role-pill role-<?php echo sanitize($row['role']); ?>"><?php echo sanitize($row['role']); ?></span></td>
                        <td data-label="Phân quyền">
                            <form method="POST" class="role-update-form" onsubmit="return confirm('Cập nhật vai trò cho người dùng này?');">
                                <input type="hidden" name="action" value="update_role">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <select name="new_role" aria-label="Chọn vai trò mới">
                                    <option value="user" <?php echo $row['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                                    <option value="admin" <?php echo $row['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                </select>
                                <button class="btn small primary" type="submit">Lưu</button>
                            </form>
                        </td>
                        <td data-label="Ngày tạo"><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                        <td data-label="Thao tác">
                            <?php if ((int)$row['id'] !== (int)($_SESSION['user']['id'] ?? 0)): ?>
                                <a class="btn small danger" href="/Web/admin/users/delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Xóa người dùng này?')">Xóa</a>
                            <?php else: ?>
                                <span class="muted-text">Tài khoản hiện tại</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state empty-card">
            <div class="empty-icon">🔎</div>
            <h3>Không tìm thấy người dùng phù hợp</h3>
            <p class="muted-text">Thử đổi từ khóa, vai trò hoặc khoảng ngày tạo.</p>
            <a class="btn primary" href="/Web/admin/users/index.php">Xem tất cả người dùng</a>
        </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
