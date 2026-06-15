<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$pageTitle = '采购管理';
$activeMenu = 'purchases';

$action = input('action', 'list');
$id = intval(input('id', 0));

if ($action == 'delete' && $id > 0) {
    execute("DELETE FROM purchase_items WHERE purchase_id = ?", [$id]);
    execute("DELETE FROM purchases WHERE id = ?", [$id]);
    alert('删除成功');
    redirect('purchases.php');
}

$schools = queryAll("SELECT id, school_name FROM schools ORDER BY id");
$suppliers = queryAll("SELECT id, supplier_name FROM suppliers WHERE status = 1 ORDER BY id");
$ingredients = queryAll("SELECT i.id, i.ingredient_name, i.unit, c.category_name FROM ingredients i LEFT JOIN ingredient_categories c ON i.category_id = c.id ORDER BY i.id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'school_id' => intval(input('school_id')),
        'supplier_id' => intval(input('supplier_id')) ?: null,
        'purchase_date' => input('purchase_date'),
        'status' => intval(input('status', 1)),
        'remark' => trim(input('remark'))
    ];
    $errors = validateRequired(['school_id'=>'学校','purchase_date'=>'采购日期'], $data);
    if (!empty($errors)) {
        alert(implode('，', $errors), 'error');
    } else {
        if ($id > 0) {
            execute("UPDATE purchases SET school_id=?, supplier_id=?, purchase_date=?, status=?, remark=? WHERE id=?",
                [$data['school_id'], $data['supplier_id'], $data['purchase_date'], $data['status'], $data['remark'], $id]);
            execute("DELETE FROM purchase_items WHERE purchase_id = ?", [$id]);
            $purchaseId = $id;
        } else {
            execute("INSERT INTO purchases (school_id, supplier_id, purchase_date, status, remark) VALUES (?,?,?,?,?)",
                array_values($data));
            $purchaseId = lastInsertId();
        }
        // 保存明细
        $totalAmount = 0;
        $items = input('items', []);
        foreach ($items as $item) {
            if (empty($item['ingredient_id']) || empty($item['quantity']) || empty($item['unit_price'])) continue;
            $amount = floatval($item['quantity']) * floatval($item['unit_price']);
            $totalAmount += $amount;
            execute("INSERT INTO purchase_items (purchase_id, ingredient_id, quantity, unit_price, amount) VALUES (?,?,?,?,?)",
                [$purchaseId, $item['ingredient_id'], $item['quantity'], $item['unit_price'], $amount]);
        }
        execute("UPDATE purchases SET total_amount = ? WHERE id = ?", [$totalAmount, $purchaseId]);
        alert('保存成功');
        redirect('purchases.php');
    }
}

$editData = null;
$editItems = [];
if ($action == 'edit' && $id > 0) {
    $editData = queryOne("SELECT * FROM purchases WHERE id = ?", [$id]);
    $editItems = queryAll("SELECT * FROM purchase_items WHERE purchase_id = ?", [$id]);
}

$page = intval(input('page', 1));
$keyword = trim(input('keyword'));
$where = "WHERE 1=1";
$params = [];
if ($keyword) {
    $where .= " AND (s.school_name LIKE ? OR sp.supplier_name LIKE ?)";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
}
$total = queryOne("SELECT COUNT(*) as c FROM purchases p LEFT JOIN schools s ON p.school_id = s.id LEFT JOIN suppliers sp ON p.supplier_id = sp.id $where", $params)['c'];
$pager = paginate($total, $page, 10);
$list = queryAll("SELECT p.*, s.school_name, sp.supplier_name FROM purchases p LEFT JOIN schools s ON p.school_id = s.id LEFT JOIN suppliers sp ON p.supplier_id = sp.id $where ORDER BY p.purchase_date DESC LIMIT {$pager['limit']} OFFSET {$pager['offset']}", $params);

include 'header.php';
?>

<?php if ($action == 'add' || $action == 'edit'): ?>
<div class="card">
    <div class="card-header"><?php echo $action == 'edit' ? '编辑采购记录' : '添加采购记录'; ?></div>
    <div class="card-body">
        <form method="post" class="row g-3" id="purchaseForm">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="col-md-4"><label class="form-label">学校 *</label>
                <select name="school_id" class="form-select" required>
                    <option value="">请选择</option>
                    <?php foreach ($schools as $s): ?>
                    <option value="<?php echo $s['id']; ?>" <?php echo ($editData['school_id'] ?? 0) == $s['id'] ? 'selected' : ''; ?>><?php echo e($s['school_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">供应商</label>
                <select name="supplier_id" class="form-select">
                    <option value="">请选择</option>
                    <?php foreach ($suppliers as $sp): ?>
                    <option value="<?php echo $sp['id']; ?>" <?php echo ($editData['supplier_id'] ?? 0) == $sp['id'] ? 'selected' : ''; ?>><?php echo e($sp['supplier_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">采购日期 *</label><input type="date" name="purchase_date" class="form-control" value="<?php echo e($editData['purchase_date'] ?? date('Y-m-d')); ?>" required></div>
            <div class="col-md-4"><label class="form-label">状态</label>
                <select name="status" class="form-select">
                    <option value="0" <?php echo ($editData['status'] ?? 1) == 0 ? 'selected' : ''; ?>>待支付</option>
                    <option value="1" <?php echo ($editData['status'] ?? 1) == 1 ? 'selected' : ''; ?>>已支付</option>
                </select>
            </div>
            <div class="col-md-8"><label class="form-label">备注</label><input type="text" name="remark" class="form-control" value="<?php echo e($editData['remark'] ?? ''); ?>"></div>

            <div class="col-12">
                <label class="form-label">采购明细</label>
                <table class="table table-bordered" id="itemsTable">
                    <thead><tr><th>食材</th><th>数量</th><th>单价</th><th>金额</th><th></th></tr></thead>
                    <tbody>
                        <?php if ($editItems): foreach ($editItems as $idx => $ei): ?>
                        <tr>
                            <td>
                                <select name="items[<?php echo $idx; ?>][ingredient_id]" class="form-select" required>
                                    <option value="">请选择</option>
                                    <?php foreach ($ingredients as $ing): ?>
                                    <option value="<?php echo $ing['id']; ?>" <?php echo $ei['ingredient_id'] == $ing['id'] ? 'selected' : ''; ?>><?php echo e($ing['ingredient_name']); ?> (<?php echo e($ing['unit']); ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="number" step="0.01" name="items[<?php echo $idx; ?>][quantity]" class="form-control qty" value="<?php echo $ei['quantity']; ?>" required></td>
                            <td><input type="number" step="0.01" name="items[<?php echo $idx; ?>][unit_price]" class="form-control price" value="<?php echo $ei['unit_price']; ?>" required></td>
                            <td><input type="text" class="form-control amt" value="<?php echo number_format($ei['amount'], 2); ?>" readonly></td>
                            <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">删除</button></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td>
                                <select name="items[0][ingredient_id]" class="form-select" required>
                                    <option value="">请选择</option>
                                    <?php foreach ($ingredients as $ing): ?>
                                    <option value="<?php echo $ing['id']; ?>"><?php echo e($ing['ingredient_name']); ?> (<?php echo e($ing['unit']); ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="number" step="0.01" name="items[0][quantity]" class="form-control qty" required></td>
                            <td><input type="number" step="0.01" name="items[0][unit_price]" class="form-control price" required></td>
                            <td><input type="text" class="form-control amt" readonly></td>
                            <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">删除</button></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem()">+ 添加明细</button>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">保存</button>
                <a href="purchases.php" class="btn btn-secondary">返回</a>
            </div>
        </form>
    </div>
</div>
<script>
let itemIdx = <?php echo count($editItems) ?: 1; ?>;
const ingredientOptions = `<?php foreach ($ingredients as $ing): ?><option value="<?php echo $ing['id']; ?>"><?php echo e($ing['ingredient_name']); ?> (<?php echo e($ing['unit']); ?>)</option><?php endforeach; ?>`;
function addItem() {
    const tbody = document.querySelector('#itemsTable tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `<td><select name="items[${itemIdx}][ingredient_id]" class="form-select" required><option value="">请选择</option>` + ingredientOptions + `</select></td><td><input type="number" step="0.01" name="items[${itemIdx}][quantity]" class="form-control qty" required></td><td><input type="number" step="0.01" name="items[${itemIdx}][unit_price]" class="form-control price" required></td><td><input type="text" class="form-control amt" readonly></td><td><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">删除</button></td>`;
    tbody.appendChild(tr);
    itemIdx++;
    bindCalc();
}
function bindCalc() {
    document.querySelectorAll('#itemsTable tbody tr').forEach(tr => {
        const calc = () => {
            const qty = parseFloat(tr.querySelector('.qty').value) || 0;
            const price = parseFloat(tr.querySelector('.price').value) || 0;
            tr.querySelector('.amt').value = (qty * price).toFixed(2);
        };
        tr.querySelector('.qty').addEventListener('input', calc);
        tr.querySelector('.price').addEventListener('input', calc);
    });
}
bindCalc();
</script>

<?php else: ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>采购记录列表</span>
        <div class="d-flex gap-2">
            <form class="d-flex gap-2" method="get">
                <input type="text" name="keyword" class="form-control form-control-sm" placeholder="搜索学校/供应商" value="<?php echo e($keyword); ?>">
                <button type="submit" class="btn btn-sm btn-outline-primary">搜索</button>
            </form>
            <a href="purchases.php?action=add" class="btn btn-sm btn-primary">+ 添加采购</a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>ID</th><th>学校</th><th>供应商</th><th>采购日期</th><th>总金额</th><th>状态</th><th>操作</th></tr></thead>
            <tbody>
            <?php foreach ($list as $item): ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo e($item['school_name']); ?></td>
                    <td><?php echo e($item['supplier_name'] ?? '-'); ?></td>
                    <td><?php echo e($item['purchase_date']); ?></td>
                    <td><?php echo number_format($item['total_amount'], 2); ?></td>
                    <td><span class="badge bg-<?php echo $item['status'] ? 'success' : 'warning'; ?>"><?php echo $item['status'] ? '已支付' : '待支付'; ?></span></td>
                    <td>
                        <a href="purchases.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary">编辑</a>
                        <a href="purchases.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger btn-delete">删除</a>
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
