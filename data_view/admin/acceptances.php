<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$pageTitle = '验收管理';
$activeMenu = 'acceptances';

$action = input('action', 'list');
$id = intval(input('id', 0));

if ($action == 'delete' && $id > 0) {
    execute("DELETE FROM ingredient_acceptances WHERE id = ?", [$id]);
    alert('删除成功');
    redirect('acceptances.php');
}

$schools = queryAll("SELECT id, school_name FROM schools ORDER BY id");
$ingredients = queryAll("SELECT id, ingredient_name, unit FROM ingredients ORDER BY id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'school_id' => intval(input('school_id')),
        'acceptance_date' => input('acceptance_date'),
        'ingredient_id' => intval(input('ingredient_id')),
        'quantity' => floatval(input('quantity', 0)),
        'quality_status' => intval(input('quality_status', 1)),
        'inspector' => trim(input('inspector'))
    ];
    $errors = validateRequired(['school_id'=>'学校','acceptance_date'=>'验收日期','ingredient_id'=>'食材'], $data);
    if (!empty($errors)) {
        alert(implode('，', $errors), 'error');
    } else {
        if ($id > 0) {
            execute("UPDATE ingredient_acceptances SET school_id=?, acceptance_date=?, ingredient_id=?, quantity=?, quality_status=?, inspector=? WHERE id=?",
                array_values(array_merge($data, ['id'=>$id])));
            alert('更新成功');
        } else {
            execute("INSERT INTO ingredient_acceptances (school_id, acceptance_date, ingredient_id, quantity, quality_status, inspector) VALUES (?,?,?,?,?,?)",
                array_values($data));
            alert('添加成功');
        }
        redirect('acceptances.php');
    }
}

$editData = null;
if ($action == 'edit' && $id > 0) {
    $editData = queryOne("SELECT * FROM ingredient_acceptances WHERE id = ?", [$id]);
}

$page = intval(input('page', 1));
$keyword = trim(input('keyword'));
$where = "WHERE 1=1";
$params = [];
if ($keyword) {
    $where .= " AND (s.school_name LIKE ? OR i.ingredient_name LIKE ?)";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
}
$total = queryOne("SELECT COUNT(*) as c FROM ingredient_acceptances a LEFT JOIN schools s ON a.school_id = s.id LEFT JOIN ingredients i ON a.ingredient_id = i.id $where", $params)['c'];
$pager = paginate($total, $page, 10);
$list = queryAll("SELECT a.*, s.school_name, i.ingredient_name FROM ingredient_acceptances a LEFT JOIN schools s ON a.school_id = s.id LEFT JOIN ingredients i ON a.ingredient_id = i.id $where ORDER BY a.acceptance_date DESC LIMIT {$pager['limit']} OFFSET {$pager['offset']}", $params);

include 'header.php';
?>

<?php if ($action == 'add' || $action == 'edit'): ?>
<div class="card">
    <div class="card-header"><?php echo $action == 'edit' ? '编辑验收记录' : '添加验收记录'; ?></div>
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
            <div class="col-md-6"><label class="form-label">验收日期 *</label><input type="date" name="acceptance_date" class="form-control" value="<?php echo e($editData['acceptance_date'] ?? date('Y-m-d')); ?>" required></div>
            <div class="col-md-6"><label class="form-label">食材 *</label>
                <select name="ingredient_id" class="form-select" required>
                    <option value="">请选择</option>
                    <?php foreach ($ingredients as $ing): ?>
                    <option value="<?php echo $ing['id']; ?>" <?php echo ($editData['ingredient_id'] ?? 0) == $ing['id'] ? 'selected' : ''; ?>><?php echo e($ing['ingredient_name']); ?> (<?php echo e($ing['unit']); ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3"><label class="form-label">数量</label><input type="number" step="0.01" name="quantity" class="form-control" value="<?php echo $editData['quantity'] ?? 0; ?>"></div>
            <div class="col-md-3"><label class="form-label">质量状态</label>
                <select name="quality_status" class="form-select">
                    <option value="2" <?php echo ($editData['quality_status'] ?? 1) == 2 ? 'selected' : ''; ?>>优良</option>
                    <option value="1" <?php echo ($editData['quality_status'] ?? 1) == 1 ? 'selected' : ''; ?>>合格</option>
                    <option value="0" <?php echo ($editData['quality_status'] ?? 1) == 0 ? 'selected' : ''; ?>>不合格</option>
                </select>
            </div>
            <div class="col-md-6"><label class="form-label">验收人</label><input type="text" name="inspector" class="form-control" value="<?php echo e($editData['inspector'] ?? ''); ?>"></div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">保存</button>
                <a href="acceptances.php" class="btn btn-secondary">返回</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>验收记录列表</span>
        <div class="d-flex gap-2">
            <form class="d-flex gap-2" method="get">
                <input type="text" name="keyword" class="form-control form-control-sm" placeholder="搜索学校/食材" value="<?php echo e($keyword); ?>">
                <button type="submit" class="btn btn-sm btn-outline-primary">搜索</button>
            </form>
            <a href="acceptances.php?action=add" class="btn btn-sm btn-primary">+ 添加验收</a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>ID</th><th>学校</th><th>日期</th><th>食材</th><th>数量</th><th>质量</th><th>验收人</th><th>操作</th></tr></thead>
            <tbody>
            <?php foreach ($list as $item): ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo e($item['school_name']); ?></td>
                    <td><?php echo e($item['acceptance_date']); ?></td>
                    <td><?php echo e($item['ingredient_name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><span class="badge bg-<?php echo $item['quality_status'] == 2 ? 'success' : ($item['quality_status'] == 1 ? 'primary' : 'danger'); ?>"><?php echo $item['quality_status'] == 2 ? '优良' : ($item['quality_status'] == 1 ? '合格' : '不合格'); ?></span></td>
                    <td><?php echo e($item['inspector']); ?></td>
                    <td>
                        <a href="acceptances.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary">编辑</a>
                        <a href="acceptances.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger btn-delete">删除</a>
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