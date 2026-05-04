<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

requireLogin();

$stmt = $conn->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC');
$stmt->bind_param('i', $_SESSION['user']['id']);
$stmt->execute();
$result = $stmt->get_result();

$pageTitle = 'Đơn hàng của tôi';
include __DIR__ . '/includes/header.php';
?>
<section class="section-headline compact">
    <div>
        <span class="section-kicker">Lịch sử mua hàng</span>
        <h2>Quản lý đơn hàng của bạn</h2>
    </div>
</section>

<div class="cart-table-wrap modern-table-wrap table-spacing-top">
    <?php if ($result->num_rows > 0): ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Địa chỉ giao hàng</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong>#<?php echo $row['id']; ?></strong></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                        <td><strong class="total-price-emphasis"><?php echo formatPrice($row['total_amount']); ?></strong></td>
                        <td>
                            <?php echo $row['payment_method'] === 'bank_transfer' ? 'Chuyển khoản' : 'Tiền mặt (COD)'; ?>
                        </td>
                        <td>
                            <?php 
                                $statusClass = '';
                                $statusText = '';
                                switch($row['status']) {
                                    case 'pending': $statusClass = 'badge warning'; $statusText = 'Chờ xử lý'; break;
                                    case 'processing': $statusClass = 'badge info'; $statusText = 'Đang giao'; break;
                                    case 'completed': $statusClass = 'badge success'; $statusText = 'Hoàn thành'; break;
                                    case 'cancelled': $statusClass = 'badge danger'; $statusText = 'Đã hủy'; break;
                                    default: $statusClass = 'badge'; $statusText = $row['status'];
                                }
                            ?>
                            <span class="<?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                        </td>
                        <td>
                            <small><?php echo sanitize($row['shipping_address']); ?></small>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state empty-card">
            <p>Bạn chưa có đơn hàng nào.</p>
            <a href="products.php" class="btn primary">Mua sắm ngay</a>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
