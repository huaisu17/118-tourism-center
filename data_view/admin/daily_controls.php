<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$pageTitle = '日管控管理';
$activeMenu = 'daily_controls';

$action = input('action', 'list');
$id = intval(input('id', 0));

if ($action == 'delete' && $id > 0) {
    execute("DELETE FROM daily_controls WHERE id = ?", [$id]);
    alert('删除成功');
    redirect('daily_controls.php');
}

$schools = queryAll("SELECT id, school_name FROM schools ORDER BY id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'school_id' => intval(input('school_id')),
        'control_date' => input('control_date'),
        'total_units' => intval(input('total_units', 0)),
        'qualified_units' => intval(input('qualified_units', 0)),
        'yellow_line_issues' => intval(input('yellow_line_issues', 0)),
        'basic_issues' => intval(input('basic_issues', 0)),
        'status' => trim(input('status', 'normal'))
    ];
    $errors = validateRequired(['school_id'=>'学校','control_date'=>'管控日期'], $data);
    if (!empty($errors)) {
        alert(implode('，', $errors), 'error');
    } else {
        if ($id > 0) {
            execute("UPDATE daily_controls SET school_id=?, control_date=?, total_units=?, qualified_units=?, yellow_line_issues=?, basic_issues=?, status=? WHERE id=?",
                array_values(array_merge($data, ['id'=>$id])));
            alert('更新成功');
        } else {
            execute("INSERT INTO daily_controls (school_id, control_date, total_units, qualified_units, yellow_line_issues, basic_issues, status) VALUES (?,?,?,?,?,?,?)",
                array_values($data));
            alert('添加成功');
        }
        redirect('daily_controls.php');
    }
}

$editData = null;
if ($action == 'edit' && $id > 0) {
    $editData = queryOne("SELECT * FROM daily_controls WHERE id = ?", [$id]);
}

$page = intval(input('page', 1));
$keyword = trim(input('keyword'));
$where = "WHERE 1=1";
$params = [];
if ($keyword) {
    $where .= " AND s.school_name LIKE ?";
    $params[] = "%$keyword%";
}
$total = queryOne("SELECT COUNT(*) as c FROM daily_controls d LEFT JOIN schools s ON d.school_id = s.id $where", $params)['c'];
$pager = paginate($total, $page, 10);
$list = queryAll("SELECT d.*, s.school_name FROM daily_controls d LEFT JOIN schools s ON d.school_id = s.id $where ORDER BY d.control_date DESC LIMIT {$pager['limit']} OFFSET {$pager['offset']}", $params);

include 'header.php';
?>

<?php if ($action == 'add' || $action == 'edit'): ?>
<div class="card">
    <div class="card-header"><?php echo $action == 'edit' ? '编辑日管控记录' : '添加日管控记录'; ?></div>
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
            <div class="col-md-6"><label class="form-label">管控日期 *</label><input type="date" name="control_date" class="form-control" value="<?php echo e($editData['control_date'] ?? date('Y-m-d')); ?>" required></div>
            <div class="col-md-3"><label class="form-label">排查单位总数</label><input type="number" name="total_units" class="form-control" value="<?php echo $editData['total_units'] ?? 0; ?>"></div>
            <div class="col-md-3"><label class="form-label">合格单位数</label><input type="number" name="qualified_units" class="form-control" value="<?php echo $editData['qualified_units'] ?? 0; ?>"></div>
            <div class="col-md-3"><label class="form-label">黄线问题数</label><input type="number" name="yellow_line_issues" class="form-control" value="<?php echo $editData['yellow_line_issues'] ?? 0; ?>"></div>
            <div class="col-md-3"><label class="form-label">基础问题数</label><input type="number" name="basic_issues" class="form-control" value="<?php echo $editData['basic_issues'] ?? 0; ?>"></div>
            <div class="col-md-6"><label class="form-label">状态</label>
                <select name="status" class="form-select">
                    <option value="normal" <?php echo ($editData['status'] ?? 'normal') == 'normal' ? 'selected' : ''; ?>>正常</option>
                    <option value="warning" <?php echo ($editData['status'] ?? '') == 'warning' ? 'selected' : ''; ?>>警告</option>
                    <option value="urgent" <?php echo ($editData['status'] ?? '') == 'urgent' ? 'selected' : ''; ?>>紧急</option>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">保存</button>
                <a href="daily_controls.php" class="btn btn-secondary">返回</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>日管控记录列表</span>
        <div class="d-flex gap-2">
            <form class="d-flex gap-2" method="get">
                <input type="text" name="keyword" class="form-control form-control-sm" placeholder="搜索学校" value="<?php echo e($keyword); ?>">
                <button type="submit" class="btn btn-sm btn-outline-primary">搜索</button>
            </form>
            <a href="daily_controls.php?action=add" class="btn btn-sm btn-primary">+ 添加日管控</a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>ID</th><th>学校</th><th>日期</th><th>排查</th><th>合格</th><th>黄线问题</th><th>基础问题</th><th>状态</th><th>操作</th></tr></thead>
            <tbody>
            <?php foreach ($list as $item): ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo e($item['school_name']); ?></td>
                    <td><?php echo e($item['control_date']); ?></td>
                    <td><?php echo $item['total_units']; ?></td>
                    <td><?php echo $item['qualified_units']; ?></td>
                    <td><?php echo $item['yellow_line_issues']; ?></td>
                    <td><?php echo $item['basic_issues']; ?></td>
                    <td><span class="badge bg-<?php echo $item['status'] == 'normal' ? 'success' : ($item['status'] == 'warning' ? 'warning' : 'danger'); ?>"><?php echo e($item['status']); ?></span></td>
                    <td>
                        <a href="daily_controls.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary">编辑</a>
                        <a href="daily_controls.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger btn-delete">删除</a>
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