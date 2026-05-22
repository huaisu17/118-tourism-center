<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
$pageTitle = '价格管理';
$activeMenu = 'prices';

$action = input('action', 'list');
$id = intval(input('id', 0));

if ($action == 'delete' && $id > 0) {
    execute("DELETE FROM price_records WHERE id = ?", [$id]);
    alert('删除成功');
    redirect('prices.php');
}

$ingredients = queryAll("SELECT id, ingredient_name, unit FROM ingredients ORDER BY id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'ingredient_id' => intval(input('ingredient_id')),
        'record_date' => input('record_date'),
        'price' => floatval(input('price', 0)),
        'region' => trim(input('region'))
    ];
    $errors = validateRequired(['ingredient_id'=>'食材','record_date'=>'记录日期','price'=>'价格'], $data);
    if (!empty($errors)) {
        alert(implode('，', $errors), 'error');
    } else {
        if ($id > 0) {
            execute("UPDATE price_records SET ingredient_id=?, record_date=?, price=?, region=? WHERE id=?",
                array_values(array_merge($data, ['id'=>$id])));
            alert('更新成功');
        } else {
            execute("INSERT INTO price_records (ingredient_id, record_date, price, region) VALUES (?,?,?,?)",
                array_values($data));
            alert('添加成功');
        }
        redirect('prices.php');
    }
}

$editData = null;
if ($action == 'edit' && $id > 0) {
    $editData = queryOne("SELECT * FROM price_records WHERE id = ?", [$id]);
}

$page = intval(input('page', 1));
$keyword = trim(input('keyword'));
$where = "WHERE 1=1";
$params = [];
if ($keyword) {
    $where .= " AND i.ingredient_name LIKE ?";
    $params[] = "%$keyword%";
}
$total = queryOne("SELECT COUNT(*) as c FROM price_records p LEFT JOIN ingredients i ON p.ingredient_id = i.id $where", $params)['c'];
$pager = paginate($total, $page, 10);
$list = queryAll("SELECT p.*, i.ingredient_name, i.unit FROM price_records p LEFT JOIN ingredients i ON p.ingredient_id = i.id $where ORDER BY p.record_date DESC LIMIT {$pager['limit']} OFFSET {$pager['offset']}", $params);

include 'header.php';
?>

<?php if ($action == 'add' || $action == 'edit'): ?>
<div class="card">
    <div class="card-header"><?php echo $action == 'edit' ? '编辑价格记录' : '添加价格记录'; ?></div>
    <div class="card-body">
        <form method="post" class="row g-3">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="col-md-6"><label class="form-label">食材 *</label>
                <select name="ingredient_id" class="form-select" required>
                    <option value="">请选择</option>
                    <?php foreach ($ingredients as $ing): ?>
                    <option value="<?php echo $ing['id']; ?>" <?php echo ($editData['ingredient_id'] ?? 0) == $ing['id'] ? 'selected' : ''; ?>><?php echo e($ing['ingredient_name']); ?> (<?php echo e($ing['unit']); ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6"><label class="form-label">记录日期 *</label><input type="date" name="record_date" class="form-control" value="<?php echo e($editData['record_date'] ?? date('Y-m-d')); ?>" required></div>
            <div class="col-md-6"><label class="form-label">价格(元) *</label><input type="number" step="0.01" name="price" class="form-control" value="<?php echo $editData['price'] ?? 0; ?>" required></div>
            <div class="col-md-6"><label class="form-label">地区</label><input type="text" name="region" class="form-control" value="<?php echo e($editData['region'] ?? ''); ?>"></div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">保存</button>
                <a href="prices.php" class="btn btn-secondary">返回</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>价格记录列表</span>
        <div class="d-flex gap-2">
            <form class="d-flex gap-2" method="get">
                <input type="text" name="keyword" class="form-control form-control-sm" placeholder="搜索食材" value="<?php echo e($keyword); ?>">
                <button type="submit" class="btn btn-sm btn-outline-primary">搜索</button>
            </form>
            <a href="prices.php?action=add" class="btn btn-sm btn-primary">+ 添加价格</a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>ID</th><th>食材</th><th>单位</th><th>日期</th><th>价格</th><th>地区</th><th>操作</th></tr></thead>
            <tbody>
            <?php foreach ($list as $item): ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo e($item['ingredient_name']); ?></td>
                    <td><?php echo e($item['unit']); ?></td>
                    <td><?php echo e($item['record_date']); ?></td>
                    <td><?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo e($item['region'] ?? '-'); ?></td>
                    <td>
                        <a href="prices.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary">编辑</a>
                        <a href="prices.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger btn-delete">删除</a>
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