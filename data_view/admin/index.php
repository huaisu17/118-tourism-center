<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
$pageTitle = '仪表盘';
$activeMenu = 'dashboard';

// 统计数据
$schoolCount = queryOne("SELECT COUNT(*) as c FROM schools")['c'];
$supplierCount = queryOne("SELECT COUNT(*) as c FROM suppliers")['c'];
$ingredientCount = queryOne("SELECT COUNT(*) as c FROM ingredients")['c'];
$purchaseTotal = queryOne("SELECT SUM(total_amount) as total FROM purchases")['total'] ?? 0;
$orderToday = queryOne("SELECT SUM(total_count) as c FROM orders WHERE order_date = CURDATE()")['c'] ?? 0;
$controlToday = queryOne("SELECT SUM(total_units) as c FROM daily_controls WHERE control_date = CURDATE()")['c'] ?? 0;

// 最近记录
$recentPurchases = queryAll("SELECT p.*, s.school_name, sp.supplier_name
    FROM purchases p
    LEFT JOIN schools s ON p.school_id = s.id
    LEFT JOIN suppliers sp ON p.supplier_id = sp.id
    ORDER BY p.created_at DESC LIMIT 5");
$recentOrders = queryAll("SELECT o.*, s.school_name FROM orders o LEFT JOIN schools s ON o.school_id = s.id ORDER BY o.order_date DESC LIMIT 5");
$recentControls = queryAll("SELECT d.*, s.school_name FROM daily_controls d LEFT JOIN schools s ON d.school_id = s.id ORDER BY d.control_date DESC LIMIT 5");

include 'header.php';
?>

<div class="row g-4 mb-4">
    <div class="col-md-4 col-lg-2">
        <div class="card text-center p-3">
            <h2 class="text-primary"><?php echo $schoolCount; ?></h2>
            <div class="text-muted">学校数量</div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card text-center p-3">
            <h2 class="text-success"><?php echo $supplierCount; ?></h2>
            <div class="text-muted">供应商数量</div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card text-center p-3">
            <h2 class="text-info"><?php echo $ingredientCount; ?></h2>
            <div class="text-muted">食材种类</div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card text-center p-3">
            <h2 class="text-warning"><?php echo number_format($purchaseTotal, 2); ?></h2>
            <div class="text-muted">采购总额(元)</div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card text-center p-3">
            <h2 class="text-danger"><?php echo $orderToday; ?></h2>
            <div class="text-muted">今日订餐数</div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card text-center p-3">
            <h2 class="text-secondary"><?php echo $controlToday; ?></h2>
            <div class="text-muted">今日排查单位</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>最近采购记录</span>
                <a href="purchases.php" class="btn btn-sm btn-outline-primary">查看更多</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>学校</th><th>金额</th><th>日期</th></tr></thead>
                    <tbody>
                    <?php foreach ($recentPurchases as $item): ?>
                        <tr>
                            <td><?php echo e($item['school_name']); ?></td>
                            <td><?php echo number_format($item['total_amount'], 2); ?></td>
                            <td><?php echo e($item['purchase_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>最近订餐记录</span>
                <a href="orders.php" class="btn btn-sm btn-outline-primary">查看更多</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>学校</th><th>人数</th><th>日期</th></tr></thead>
                    <tbody>
                    <?php foreach ($recentOrders as $item): ?>
                        <tr>
                            <td><?php echo e($item['school_name']); ?></td>
                            <td><?php echo $item['total_count']; ?></td>
                            <td><?php echo e($item['order_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>最近日管控记录</span>
                <a href="daily_controls.php" class="btn btn-sm btn-outline-primary">查看更多</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>学校</th><th>排查</th><th>合格</th></tr></thead>
                    <tbody>
                    <?php foreach ($recentControls as $item): ?>
                        <tr>
                            <td><?php echo e($item['school_name']); ?></td>
                            <td><?php echo $item['total_units']; ?></td>
                            <td><?php echo $item['qualified_units']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
