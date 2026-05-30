<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
$pageTitle = '食材管理';
$activeMenu = 'ingredients';

$action = input('action', 'list');
$id = intval(input('id', 0));
$catAction = input('cat_action', '');
$catId = intval(input('cat_id', 0));

// 删除分类
if ($catAction == 'delete_cat' && $catId > 0) {
    execute("DELETE FROM ingredient_categories WHERE id = ?", [$catId]);
    alert('分类删除成功');
    redirect('ingredients.php');
}

// 保存分类
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_category'])) {
    $catName = trim(input('category_name'));
    $sortOrder = intval(input('sort_order', 0));
    if (empty($catName)) {
        alert('分类名称不能为空', 'error');
    } else {
        if ($catId > 0) {
            execute("UPDATE ingredient_categories SET category_name=?, sort_order=? WHERE id=?", [$catName, $sortOrder, $catId]);
            alert('分类更新成功');
        } else {
            execute("INSERT INTO ingredient_categories (category_name, sort_order) VALUES (?,?)", [$catName, $sortOrder]);
            alert('分类添加成功');
        }
        redirect('ingredients.php');
    }
}

// 删除食材
if ($action == 'delete' && $id > 0) {
    execute("DELETE FROM ingredients WHERE id = ?", [$id]);
    alert('删除成功');
    redirect('ingredients.php');
}

// 保存食材
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['save_category'])) {
    $data = [
        'ingredient_name' => trim(input('ingredient_name')),
        'category_id' => intval(input('category_id')),
        'unit' => trim(input('unit'))
    ];
    $errors = validateRequired(['ingredient_name'=>'食材名称','category_id'=>'所属分类'], $data);
    if (!empty($errors)) {
        alert(implode('，', $errors), 'error');
    } else {
        if ($id > 0) {
            execute("UPDATE ingredients SET ingredient_name=?, category_id=?, unit=? WHERE id=?",
                [$data['ingredient_name'], $data['category_id'], $data['unit'], $id]);
            alert('更新成功');
        } else {
            execute("INSERT INTO ingredients (ingredient_name, category_id, unit) VALUES (?,?,?)",
                array_values($data));
            alert('添加成功');
        }
        redirect('ingredients.php');
    }
}

$editData = null;
if ($action == 'edit' && $id > 0) {
    $editData = queryOne("SELECT * FROM ingredients WHERE id = ?", [$id]);
}

$categories = queryAll("SELECT * FROM ingredient_categories ORDER BY sort_order, id");
$page = intval(input('page', 1));
$keyword = trim(input('keyword'));
$where = "WHERE 1=1";
$params = [];
if ($keyword) {
    $where .= " AND i.ingredient_name LIKE ?";
    $params[] = "%$keyword%";
}
$total = queryOne("SELECT COUNT(*) as c FROM ingredients i $where", $params)['c'];
$pager = paginate($total, $page, 10);
$list = queryAll("SELECT i.*, c.category_name FROM ingredients i LEFT JOIN ingredient_categories c ON i.category_id = c.id $where ORDER BY i.id DESC LIMIT {$pager['limit']} OFFSET {$pager['offset']}", $params);

// 编辑分类数据
$editCat = null;
if ($catAction == 'edit_cat' && $catId > 0) {
    $editCat = queryOne("SELECT * FROM ingredient_categories WHERE id = ?", [$catId]);
}

include 'header.php';
?>

<?php if ($action == 'add' || $action == 'edit'): ?>
<div class="card">
    <div class="card-header"><?php echo $action == 'edit' ? '编辑食材' : '添加食材'; ?></div>
    <div class="card-body">
        <form method="post" class="row g-3">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="col-md-6"><label class="form-label">食材名称 *</label><input type="text" name="ingredient_name" class="form-control" value="<?php echo e($editData['ingredient_name'] ?? ''); ?>" required></div>
            <div class="col-md-6"><label class="form-label">所属分类 *</label>
                <select name="category_id" class="form-select" required>
                    <option value="">请选择</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo ($editData['category_id'] ?? 0) == $cat['id'] ? 'selected' : ''; ?>><?php echo e($cat['category_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6"><label class="form-label">计量单位</label><input type="text" name="unit" class="form-control" value="<?php echo e($editData['unit'] ?? 'kg'); ?>"></div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">保存</button>
                <a href="ingredients.php" class="btn btn-secondary">返回</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<div class="row g-4">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>食材分类</span>
            </div>
            <div class="card-body">
                <form method="post" class="d-flex gap-2 mb-3">
                    <input type="hidden" name="cat_id" value="<?php echo $catId; ?>">
                    <input type="text" name="category_name" class="form-control form-control-sm" placeholder="分类名称" value="<?php echo e($editCat['category_name'] ?? ''); ?>" required>
                    <input type="number" name="sort_order" class="form-control form-control-sm" placeholder="排序" value="<?php echo $editCat['sort_order'] ?? 0; ?>" style="width:70px">
                    <button type="submit" name="save_category" class="btn btn-sm btn-primary"><?php echo $editCat ? '更新' : '添加'; ?></button>
                </form>
                <ul class="list-group list-group-flush">
                    <?php foreach ($categories as $cat): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo e($cat['category_name']); ?>
                        <div>
                            <a href="?cat_action=edit_cat&cat_id=<?php echo $cat['id']; ?>" class="text-primary"><i class="bi bi-pencil-square"></i></a>
                            <a href="?cat_action=delete_cat&cat_id=<?php echo $cat['id']; ?>" class="text-danger ms-2 btn-delete"><i class="bi bi-trash"></i></a>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>食材列表</span>
                <div class="d-flex gap-2">
                    <form class="d-flex gap-2" method="get">
                        <input type="text" name="keyword" class="form-control form-control-sm" placeholder="搜索食材" value="<?php echo e($keyword); ?>">
                        <button type="submit" class="btn btn-sm btn-outline-primary">搜索</button>
                    </form>
                    <a href="ingredients.php?action=add" class="btn btn-sm btn-primary">+ 添加食材</a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>ID</th><th>食材名称</th><th>分类</th><th>单位</th><th>操作</th></tr></thead>
                    <tbody>
                    <?php foreach ($list as $item): ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td><?php echo e($item['ingredient_name']); ?></td>
                            <td><span class="badge bg-info"><?php echo e($item['category_name']); ?></span></td>
                            <td><?php echo e($item['unit']); ?></td>
                            <td>
                                <a href="ingredients.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary">编辑</a>
                                <a href="ingredients.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger btn-delete">删除</a>
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
    </div>
</div>
<?php endif; ?>

<?php include 'footer.php'; ?>
