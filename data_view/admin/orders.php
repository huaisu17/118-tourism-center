<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$pageTitle = '订餐管理';
$activeMenu = 'orders';

$action = input('action', 'list');
$id = intval(input('id', 0));

if ($action == 'delete' && $id > 0) {
    execute("DELETE FROM orders WHERE id = ?", [$id]);
    alert('删除成功');
    redirect('orders.php');
}

$schools = queryAll("SELECT id, school_name FROM schools ORDER BY id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'school_id' => intval(input('school_id')),
        'order_date' => input('order_date'),
        'student_count' => intval(input('student_count', 0)),
        'teacher_count' => intval(input('teacher_count', 0)),
        'total_count' => intval(input('student_count', 0)) + intval(input('teacher_count', 0)),
        'total_amount' => floatval(input('total_amount', 0)),
        'ingredient_category' => trim(input('ingredient_category'))
    ];
    $errors = validateRequired(['school_id'=>'学校','order_date'=>'订餐日期'], $data);
    if (!empty($errors)) {
        alert(implode('，', $errors), 'error');
    } else {
        if ($id > 0) {
            execute("UPDATE orders SET school_id=?, order_date=?, student_count=?, teacher_count=?, total_count=?, total_amount=?, ingredient_category=? WHERE id=?",
                array_values(array_merge($data, ['id'=>$id])));
            alert('更新成功');
        } else {
            execute("INSERT INTO orders (school_id, order_date, student_count, teacher_count, total_count, total_amount, ingredient_category) VALUES (?,?,?,?,?,?,?)",
                array_values($data));
            alert('添加成功');
        }
        redirect('orders.php');
    }
}

$editData = null;
if ($action == 'edit' && $id > 0) {
    $editData = queryOne("SELECT * FROM orders WHERE id = ?", [$id]);
}

$page = intval(input('page', 1));
$keyword = trim(input('keyword'));
$where = "WHERE 1=1";
$params = [];
if ($keyword) {
    $where .= " AND s.school_name LIKE ?";
    $params[] = "%$keyword%";
}
$total = queryOne("SELECT COUNT(*) as c FROM orders o LEFT JOIN schools s ON o.school_id = s.id $where", $params)['c'];
$pager = paginate($total, $page, 10);
$list = queryAll("SELECT o.*, s.school_name FROM orders o LEFT JOIN schools s ON o.school_id = s.id $where ORDER BY o.order_date DESC LIMIT {$pager['limit']} OFFSET {$pager['offset']}", $params);

include 'header.php';
?>

<?php if ($action == 'add' || $action == 'edit'): ?>
<div class="card">
    <div class="card-header"><?php echo $action == 'edit' ? '编辑订餐记录' : '添加订餐记录'; ?></div>
    <div class="card-body">
        <form method="post" class="row g-3">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="col-md-6"><label class="form-label">学校 *</label>
                <select name="school_id" class="form-select" required>
                    <option value="">请选择</option>
                    <?php foreach ($schools as $s): ?>
                    <option value="<?php echo $s['id']; ?>" <?php echo ($editData['school_id'] ?? 0) == $s['id'] ? 'selected' : ''; ?>><?php echo e($s['school_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6"><label class="form-label">订餐日期 *</label><input type="date" name="order_date" class="form-control" value="<?php echo e($editData['order_date'] ?? date('Y-m-d')); ?>" required></div>
            <div class="col-md-4"><label class="form-label">学生订餐数</label><input type="number" name="student_count" class="form-control" value="<?php echo $editData['student_count'] ?? 0; ?>"></div>
            <div class="col-md-4"><label class="form-label">教师订餐数</label><input type="number" name="teacher_count" class="form-control" value="<?php echo $editData['teacher_count'] ?? 0; ?>"></div>
            <div class="col-md-4"><label class="form-label">总金额(元)</label><input type="number" step="0.01" name="total_amount" class="form-control" value="<?php echo $editData['total_amount'] ?? 0; ?>"></div>
            <div class="col-md-6"><label class="form-label">主要营养类别</label>
                <select name="ingredient_category" class="form-select">
                    <option value="">请选择</option>
                    <?php foreach (['副食','生鲜','粮油','其他'] as $cat): ?>
                    <option value="<?php echo $cat; ?>" <?php echo ($editData['ingredient_category'] ?? '') == $cat ? 'selected' : ''; ?>><?php echo $cat; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">保存</button>
                <a href="orders.php" class="btn btn-secondary">返回</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>订餐记录列表</span>
        <div class="d-flex gap-2">
            <form class="d-flex gap-2" method="get">
                <input type="text" name="keyword" class="form-control form-control-sm" placeholder="搜索学校" value="<?php echo e($keyword); ?>">
                <button type="submit" class="btn btn-sm btn-outline-primary">搜索</button>
            </form>
            <a href="orders.php?action=add" class="btn btn-sm btn-primary">+ 添加订餐</a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>ID</th><th>学校</th><th>日期</th><th>学生</th><th>教师</th><th>总数</th><th>金额</th><th>操作</th></tr></thead>
            <tbody>
            <?php foreach ($list as $item): ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo e($item['school_name']); ?></td>
                    <td><?php echo e($item['order_date']); ?></td>
                    <td><?php echo $item['student_count']; ?></td>
                    <td><?php echo $item['teacher_count']; ?></td>
                    <td><?php echo $item['total_count']; ?></td>
                    <td><?php echo number_format($item['total_amount'], 2); ?></td>
                    <td>
                        <a href="orders.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary">编辑</a>
                        <a href="orders.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger btn-delete">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($pager['totalPage'] > 1): ?>
    <div class="card-footer">
        <nav><ul class="pagination pagination-sm justify-content-center mb-0">
            <?php for ($i = 1; $i <= $pager['totalPage']; $i++): ?>
            <li class="page-item <?php echo $i == $pager['page'] ? 'active' : ''; ?>"><a class="page-link" href="?page=<?php echo $i; ?>&keyword=<?php echo e($keyword); ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>
        </ul></nav>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php include 'footer.php'; ?>
