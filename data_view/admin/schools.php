<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
$pageTitle = '学校管理';
$activeMenu = 'schools';

$action = input('action', 'list');
$id = intval(input('id', 0));

// 删除
if ($action == 'delete' && $id > 0) {
    execute("DELETE FROM schools WHERE id = ?", [$id]);
    alert('删除成功');
    redirect('schools.php');
}

// 保存
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'school_name' => trim(input('school_name')),
        'region' => trim(input('region')),
        'address' => trim(input('address')),
        'contact_person' => trim(input('contact_person')),
        'contact_phone' => trim(input('contact_phone')),
        'student_count' => intval(input('student_count', 0)),
        'teacher_count' => intval(input('teacher_count', 0))
    ];
    $errors = validateRequired(['school_name'=>'学校名称','region'=>'所属区域'], $data);
    if (!empty($errors)) {
        alert(implode('，', $errors), 'error');
    } else {
        if ($id > 0) {
            execute("UPDATE schools SET school_name=?, region=?, address=?, contact_person=?, contact_phone=?, student_count=?, teacher_count=? WHERE id=?",
                array_values(array_merge($data, ['id'=>$id])));
            alert('更新成功');
        } else {
            execute("INSERT INTO schools (school_name, region, address, contact_person, contact_phone, student_count, teacher_count) VALUES (?,?,?,?,?,?,?)",
                array_values($data));
            alert('添加成功');
        }
        redirect('schools.php');
    }
}

// 编辑数据
$editData = null;
if ($action == 'edit' && $id > 0) {
    $editData = queryOne("SELECT * FROM schools WHERE id = ?", [$id]);
}

// 列表查询
$page = intval(input('page', 1));
$keyword = trim(input('keyword'));
$where = "WHERE 1=1";
$params = [];
if ($keyword) {
    $where .= " AND (school_name LIKE ? OR region LIKE ?)";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
}
$total = queryOne("SELECT COUNT(*) as c FROM schools $where", $params)['c'];
$pager = paginate($total, $page, 10);
$list = queryAll("SELECT * FROM schools $where ORDER BY id DESC LIMIT {$pager['limit']} OFFSET {$pager['offset']}", $params);

include 'header.php';
?>

<?php if ($action == 'add' || $action == 'edit'): ?>
<div class="card">
    <div class="card-header"><?php echo $action == 'edit' ? '编辑学校' : '添加学校'; ?></div>
    <div class="card-body">
        <form method="post" class="row g-3">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="col-md-6"><label class="form-label">学校名称 *</label><input type="text" name="school_name" class="form-control" value="<?php echo e($editData['school_name'] ?? ''); ?>" required></div>
            <div class="col-md-6"><label class="form-label">所属区域 *</label><input type="text" name="region" class="form-control" value="<?php echo e($editData['region'] ?? ''); ?>" required></div>
            <div class="col-md-6"><label class="form-label">详细地址</label><input type="text" name="address" class="form-control" value="<?php echo e($editData['address'] ?? ''); ?>"></div>
            <div class="col-md-6"><label class="form-label">联系人</label><input type="text" name="contact_person" class="form-control" value="<?php echo e($editData['contact_person'] ?? ''); ?>"></div>
            <div class="col-md-6"><label class="form-label">联系电话</label><input type="text" name="contact_phone" class="form-control" value="<?php echo e($editData['contact_phone'] ?? ''); ?>"></div>
            <div class="col-md-3"><label class="form-label">学生人数</label><input type="number" name="student_count" class="form-control" value="<?php echo $editData['student_count'] ?? 0; ?>"></div>
            <div class="col-md-3"><label class="form-label">教师人数</label><input type="number" name="teacher_count" class="form-control" value="<?php echo $editData['teacher_count'] ?? 0; ?>"></div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">保存</button>
                <a href="schools.php" class="btn btn-secondary">返回</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>学校列表</span>
        <div class="d-flex gap-2">
            <form class="d-flex gap-2" method="get">
                <input type="text" name="keyword" class="form-control form-control-sm" placeholder="搜索学校/区域" value="<?php echo e($keyword); ?>">
                <button type="submit" class="btn btn-sm btn-outline-primary">搜索</button>
            </form>
            <a href="schools.php?action=add" class="btn btn-sm btn-primary">+ 添加学校</a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>ID</th><th>学校名称</th><th>区域</th><th>联系人</th><th>学生</th><th>教师</th><th>操作</th></tr></thead>
            <tbody>
            <?php foreach ($list as $item): ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo e($item['school_name']); ?></td>
                    <td><?php echo e($item['region']); ?></td>
                    <td><?php echo e($item['contact_person']); ?></td>
                    <td><?php echo $item['student_count']; ?></td>
                    <td><?php echo $item['teacher_count']; ?></td>
                    <td>
                        <a href="schools.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary">编辑</a>
                        <a href="schools.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger btn-delete">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($pager['totalPage'] > 1): ?>
    <div class="card-footer">
        <nav>
            <ul class="pagination pagination-sm justify-content-center mb-0">
                <?php for ($i = 1; $i <= $pager['totalPage']; $i++): ?>
                <li class="page-item <?php echo $i == $pager['page'] ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&keyword=<?php echo e($keyword); ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php include 'footer.php'; ?>