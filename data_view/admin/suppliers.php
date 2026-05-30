<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
$pageTitle = '供应商管理';
$activeMenu = 'suppliers';

$action = input('action', 'list');
$id = intval(input('id', 0));

if ($action == 'delete' && $id > 0) {
    execute("DELETE FROM suppliers WHERE id = ?", [$id]);
    alert('删除成功');
    redirect('suppliers.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'supplier_name' => trim(input('supplier_name')),
        'contact_person' => trim(input('contact_person')),
        'contact_phone' => trim(input('contact_phone')),
        'address' => trim(input('address')),
        'score' => floatval(input('score', 0)),
        'grade' => trim(input('grade')),
        'status' => intval(input('status', 1))
    ];
    $errors = validateRequired(['supplier_name'=>'供应商名称'], $data);
    if (!empty($errors)) {
        alert(implode('，', $errors), 'error');
    } else {
        if ($id > 0) {
            execute("UPDATE suppliers SET supplier_name=?, contact_person=?, contact_phone=?, address=?, score=?, grade=?, status=? WHERE id=?",
                array_values(array_merge($data, ['id'=>$id])));
            alert('更新成功');
        } else {
            execute("INSERT INTO suppliers (supplier_name, contact_person, contact_phone, address, score, grade, status) VALUES (?,?,?,?,?,?,?)",
                array_values($data));
            alert('添加成功');
        }
        redirect('suppliers.php');
    }
}

$editData = null;
if ($action == 'edit' && $id > 0) {
    $editData = queryOne("SELECT * FROM suppliers WHERE id = ?", [$id]);
}

$page = intval(input('page', 1));
$keyword = trim(input('keyword'));
$where = "WHERE 1=1";
$params = [];
if ($keyword) {
    $where .= " AND supplier_name LIKE ?";
    $params[] = "%$keyword%";
}
$total = queryOne("SELECT COUNT(*) as c FROM suppliers $where", $params)['c'];
$pager = paginate($total, $page, 10);
$list = queryAll("SELECT * FROM suppliers $where ORDER BY id DESC LIMIT {$pager['limit']} OFFSET {$pager['offset']}", $params);

include 'header.php';
?>

<?php if ($action == 'add' || $action == 'edit'): ?>
<div class="card">
    <div class="card-header"><?php echo $action == 'edit' ? '编辑供应商' : '添加供应商'; ?></div>
    <div class="card-body">
        <form method="post" class="row g-3">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="col-md-6"><label class="form-label">供应商名称 *</label><input type="text" name="supplier_name" class="form-control" value="<?php echo e($editData['supplier_name'] ?? ''); ?>" required></div>
            <div class="col-md-6"><label class="form-label">联系人</label><input type="text" name="contact_person" class="form-control" value="<?php echo e($editData['contact_person'] ?? ''); ?>"></div>
            <div class="col-md-6"><label class="form-label">联系电话</label><input type="text" name="contact_phone" class="form-control" value="<?php echo e($editData['contact_phone'] ?? ''); ?>"></div>
            <div class="col-md-6"><label class="form-label">地址</label><input type="text" name="address" class="form-control" value="<?php echo e($editData['address'] ?? ''); ?>"></div>
            <div class="col-md-4"><label class="form-label">评分</label><input type="number" step="0.01" name="score" class="form-control" value="<?php echo $editData['score'] ?? 0; ?>"></div>
            <div class="col-md-4"><label class="form-label">评级</label>
                <select name="grade" class="form-select">
                    <option value="">请选择</option>
                    <?php foreach (['A','B','C','D','E'] as $g): ?>
                    <option value="<?php echo $g; ?>" <?php echo ($editData['grade'] ?? '') == $g ? 'selected' : ''; ?>><?php echo $g; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">状态</label>
                <select name="status" class="form-select">
                    <option value="1" <?php echo ($editData['status'] ?? 1) == 1 ? 'selected' : ''; ?>>合作中</option>
                    <option value="0" <?php echo ($editData['status'] ?? 1) == 0 ? 'selected' : ''; ?>>停用</option>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">保存</button>
                <a href="suppliers.php" class="btn btn-secondary">返回</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>供应商列表</span>
        <div class="d-flex gap-2">
            <form class="d-flex gap-2" method="get">
                <input type="text" name="keyword" class="form-control form-control-sm" placeholder="搜索供应商" value="<?php echo e($keyword); ?>">
                <button type="submit" class="btn btn-sm btn-outline-primary">搜索</button>
            </form>
            <a href="suppliers.php?action=add" class="btn btn-sm btn-primary">+ 添加供应商</a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>ID</th><th>供应商</th><th>联系人</th><th>评分</th><th>评级</th><th>状态</th><th>操作</th></tr></thead>
            <tbody>
            <?php foreach ($list as $item): ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo e($item['supplier_name']); ?></td>
                    <td><?php echo e($item['contact_person']); ?></td>
                    <td><?php echo $item['score']; ?></td>
                    <td><span class="badge bg-<?php echo $item['grade'] == 'A' ? 'success' : ($item['grade'] == 'B' ? 'primary' : ($item['grade'] == 'C' ? 'warning' : 'secondary')); ?>"><?php echo e($item['grade']); ?></span></td>
                    <td><span class="badge bg-<?php echo $item['status'] ? 'success' : 'secondary'; ?>"><?php echo $item['status'] ? '合作中' : '停用'; ?></span></td>
                    <td>
                        <a href="suppliers.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary">编辑</a>
                        <a href="suppliers.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger btn-delete">删除</a>
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