<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$pageTitle = '调度管理';
$activeMenu = 'dispatches';

$action = input('action', 'list');
$id = intval(input('id', 0));

if ($action == 'delete' && $id > 0) {
    execute("DELETE FROM monthly_dispatches WHERE id = ?", [$id]);
    alert('删除成功');
    redirect('dispatches.php');
}

$schools = queryAll("SELECT id, school_name FROM schools ORDER BY id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'school_id' => intval(input('school_id')),
        'month' => trim(input('month')),
        'dispatch_name' => trim(input('dispatch_name')),
        'status' => trim(input('status', 'normal')),
        'content' => trim(input('content'))
    ];
    $errors = validateRequired(['school_id'=>'学校','month'=>'月份','dispatch_name'=>'调度名称'], $data);
    if (!empty($errors)) {
        alert(implode('，', $errors), 'error');
    } else {
        if ($id > 0) {
            execute("UPDATE monthly_dispatches SET school_id=?, month=?, dispatch_name=?, status=?, content=? WHERE id=?",
                array_values(array_merge($data, ['id'=>$id])));
            alert('更新成功');
        } else {
            execute("INSERT INTO monthly_dispatches (school_id, month, dispatch_name, status, content) VALUES (?,?,?,?,?)",
                array_values($data));
            alert('添加成功');
        }
        redirect('dispatches.php');
    }
}

$editData = null;
if ($action == 'edit' && $id > 0) {
    $editData = queryOne("SELECT * FROM monthly_dispatches WHERE id = ?", [$id]);
}

$page = intval(input('page', 1));
$keyword = trim(input('keyword'));
$where = "WHERE 1=1";
$params = [];
if ($keyword) {
    $where .= " AND (s.school_name LIKE ? OR m.dispatch_name LIKE ?)";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
}
$total = queryOne("SELECT COUNT(*) as c FROM monthly_dispatches m LEFT JOIN schools s ON m.school_id = s.id $where", $params)['c'];
$pager = paginate($total, $page, 10);
$list = queryAll("SELECT m.*, s.school_name FROM monthly_dispatches m LEFT JOIN schools s ON m.school_id = s.id $where ORDER BY m.month DESC LIMIT {$pager['limit']} OFFSET {$pager['offset']}", $params);

include 'header.php';
?>

<?php if ($action == 'add' || $action == 'edit'): ?>
<div class="card">
    <div class="card-header"><?php echo $action == 'edit' ? '编辑调度记录' : '添加调度记录'; ?></div>
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
            <div class="col-md-6"><label class="form-label">月份 *</label><input type="month" name="month" class="form-control" value="<?php echo e($editData['month'] ?? date('Y-m')); ?>" required></div>
            <div class="col-md-6"><label class="form-label">调度名称 *</label><input type="text" name="dispatch_name" class="form-control" value="<?php echo e($editData['dispatch_name'] ?? ''); ?>" required></div>
            <div class="col-md-6"><label class="form-label">状态</label>
                <select name="status" class="form-select">
                    <option value="normal" <?php echo ($editData['status'] ?? 'normal') == 'normal' ? 'selected' : ''; ?>>正常</option>
                    <option value="warning" <?php echo ($editData['status'] ?? '') == 'warning' ? 'selected' : ''; ?>>警告</option>
                    <option value="urgent" <?php echo ($editData['status'] ?? '') == 'urgent' ? 'selected' : ''; ?>>紧急</option>
                    <option value="done" <?php echo ($editData['status'] ?? '') == 'done' ? 'selected' : ''; ?>>已完成</option>
                </select>
            </div>
            <div class="col-12"><label class="form-label">调度内容</label><textarea name="content" class="form-control" rows="4"><?php echo e($editData['content'] ?? ''); ?></textarea></div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">保存</button>
                <a href="dispatches.php" class="btn btn-secondary">返回</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>月调度记录列表</span>
        <div class="d-flex gap-2">
            <form class="d-flex gap-2" method="get">
                <input type="text" name="keyword" class="form-control form-control-sm" placeholder="搜索学校/调度名称" value="<?php echo e($keyword); ?>">
                <button type="submit" class="btn btn-sm btn-outline-primary">搜索</button>
            </form>
            <a href="dispatches.php?action=add" class="btn btn-sm btn-primary">+ 添加调度</a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>ID</th><th>学校</th><th>月份</th><th>调度名称</th><th>状态</th><th>操作</th></tr></thead>
            <tbody>
            <?php foreach ($list as $item): ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo e($item['school_name']); ?></td>
                    <td><?php echo e($item['month']); ?></td>
                    <td><?php echo e($item['dispatch_name']); ?></td>
                    <td><span class="badge bg-<?php echo $item['status'] == 'done' ? 'success' : ($item['status'] == 'normal' ? 'primary' : ($item['status'] == 'warning' ? 'warning' : 'danger')); ?>"><?php echo e($item['status']); ?></span></td>
                    <td>
                        <a href="dispatches.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary">编辑</a>
                        <a href="dispatches.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger btn-delete">删除</a>
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