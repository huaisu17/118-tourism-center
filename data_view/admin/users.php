<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();
$pageTitle = '用户管理';
$activeMenu = 'users';

$action = input('action', 'list');
$id = intval(input('id', 0));

if ($action == 'delete' && $id > 0) {
    if ($id == $_SESSION['user_id']) {
        alert('不能删除当前登录用户', 'error');
    } else {
        execute("DELETE FROM users WHERE id = ?", [$id]);
        alert('删除成功');
    }
    redirect('users.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'username' => trim(input('username')),
        'password' => input('password'),
        'real_name' => trim(input('real_name')),
        'role' => intval(input('role', 1)),
        'status' => intval(input('status', 1))
    ];
    $errors = validateRequired(['username'=>'用户名','real_name'=>'真实姓名'], $data);
    if (!empty($errors)) {
        alert(implode('，', $errors), 'error');
    } else {
        $exists = queryOne("SELECT id FROM users WHERE username = ?", [$data['username']]);
        if (!$id && $exists) {
            alert('用户名已存在', 'error');
            redirect('users.php');
        }
        if ($id > 0) {
            if (!empty($data['password'])) {
                execute("UPDATE users SET username=?, password=?, real_name=?, role=?, status=? WHERE id=?",
                    [$data['username'], md5($data['password']), $data['real_name'], $data['role'], $data['status'], $id]);
            } else {
                execute("UPDATE users SET username=?, real_name=?, role=?, status=? WHERE id=?",
                    [$data['username'], $data['real_name'], $data['role'], $data['status'], $id]);
            }
            alert('更新成功');
        } else {
            if (empty($data['password'])) {
                alert('密码不能为空', 'error');
                redirect('users.php?action=add');
            }
            execute("INSERT INTO users (username, password, real_name, role, status) VALUES (?,?,?,?,?)",
                [$data['username'], md5($data['password']), $data['real_name'], $data['role'], $data['status']]);
            alert('添加成功');
        }
        redirect('users.php');
    }
}

$editData = null;
if ($action == 'edit' && $id > 0) {
    $editData = queryOne("SELECT * FROM users WHERE id = ?", [$id]);
}

$page = intval(input('page', 1));
$keyword = trim(input('keyword'));
$where = "WHERE 1=1";
$params = [];
if ($keyword) {
    $where .= " AND (username LIKE ? OR real_name LIKE ?)";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
}
$total = queryOne("SELECT COUNT(*) as c FROM users $where", $params)['c'];
$pager = paginate($total, $page, 10);
$list = queryAll("SELECT * FROM users $where ORDER BY id DESC LIMIT {$pager['limit']} OFFSET {$pager['offset']}", $params);

include 'header.php';
?>

<?php if ($action == 'add' || $action == 'edit'): ?>
<div class="card">
    <div class="card-header"><?php echo $action == 'edit' ? '编辑用户' : '添加用户'; ?></div>
    <div class="card-body">
        <form method="post" class="row g-3">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="col-md-6"><label class="form-label">用户名 *</label><input type="text" name="username" class="form-control" value="<?php echo e($editData['username'] ?? ''); ?>" required></div>
            <div class="col-md-6"><label class="form-label">密码 <?php echo $action == 'edit' ? '(留空不修改)' : '*'; ?></label><input type="password" name="password" class="form-control" <?php echo $action == 'add' ? 'required' : ''; ?>></div>
            <div class="col-md-6"><label class="form-label">真实姓名 *</label><input type="text" name="real_name" class="form-control" value="<?php echo e($editData['real_name'] ?? ''); ?>" required></div>
            <div class="col-md-3"><label class="form-label">角色</label>
                <select name="role" class="form-select">
                    <option value="0" <?php echo ($editData['role'] ?? 1) == 0 ? 'selected' : ''; ?>>管理员</option>
                    <option value="1" <?php echo ($editData['role'] ?? 1) == 1 ? 'selected' : ''; ?>>普通用户</option>
                </select>
            </div>
            <div class="col-md-3"><label class="form-label">状态</label>
                <select name="status" class="form-select">
                    <option value="1" <?php echo ($editData['status'] ?? 1) == 1 ? 'selected' : ''; ?>>启用</option>
                    <option value="0" <?php echo ($editData['status'] ?? 1) == 0 ? 'selected' : ''; ?>>禁用</option>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">保存</button>
                <a href="users.php" class="btn btn-secondary">返回</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>用户列表</span>
        <div class="d-flex gap-2">
            <form class="d-flex gap-2" method="get">
                <input type="text" name="keyword" class="form-control form-control-sm" placeholder="搜索用户名/姓名" value="<?php echo e($keyword); ?>">
                <button type="submit" class="btn btn-sm btn-outline-primary">搜索</button>
            </form>
            <a href="users.php?action=add" class="btn btn-sm btn-primary">+ 添加用户</a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>ID</th><th>用户名</th><th>真实姓名</th><th>角色</th><th>状态</th><th>创建时间</th><th>操作</th></tr></thead>
            <tbody>
            <?php foreach ($list as $item): ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo e($item['username']); ?></td>
                    <td><?php echo e($item['real_name']); ?></td>
                    <td><span class="badge bg-<?php echo $item['role'] == 0 ? 'danger' : 'primary'; ?>"><?php echo $item['role'] == 0 ? '管理员' : '普通用户'; ?></span></td>
                    <td><span class="badge bg-<?php echo $item['status'] ? 'success' : 'secondary'; ?>"><?php echo $item['status'] ? '启用' : '禁用'; ?></span></td>
                    <td><?php echo e($item['created_at']); ?></td>
                    <td>
                        <a href="users.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary">编辑</a>
                        <?php if ($item['id'] != $_SESSION['user_id']): ?>
                        <a href="users.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger btn-delete">删除</a>
                        <?php endif; ?>
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