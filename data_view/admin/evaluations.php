<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$pageTitle = '评价管理';
$activeMenu = 'evaluations';

$action = input('action', 'list');
$id = intval(input('id', 0));

if ($action == 'delete' && $id > 0) {
    execute("DELETE FROM evaluations WHERE id = ?", [$id]);
    alert('删除成功');
    redirect('evaluations.php');
}

$schools = queryAll("SELECT id, school_name FROM schools ORDER BY id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'school_id' => intval(input('school_id')),
        'evaluation_date' => input('evaluation_date'),
        'evaluator_type' => trim(input('evaluator_type', 'student')),
        'score' => floatval(input('score', 0)),
        'content' => trim(input('content'))
    ];
    $errors = validateRequired(['school_id'=>'学校','evaluation_date'=>'评价日期'], $data);
    if (!empty($errors)) {
        alert(implode('，', $errors), 'error');
    } else {
        if ($id > 0) {
            execute("UPDATE evaluations SET school_id=?, evaluation_date=?, evaluator_type=?, score=?, content=? WHERE id=?",
                array_values(array_merge($data, ['id'=>$id])));
            alert('更新成功');
        } else {
            execute("INSERT INTO evaluations (school_id, evaluation_date, evaluator_type, score, content) VALUES (?,?,?,?,?)",
                array_values($data));
            alert('添加成功');
        }
        redirect('evaluations.php');
    }
}

$editData = null;
if ($action == 'edit' && $id > 0) {
    $editData = queryOne("SELECT * FROM evaluations WHERE id = ?", [$id]);
}

$page = intval(input('page', 1));
$keyword = trim(input('keyword'));
$where = "WHERE 1=1";
$params = [];
if ($keyword) {
    $where .= " AND (s.school_name LIKE ? OR e.content LIKE ?)";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
}
$total = queryOne("SELECT COUNT(*) as c FROM evaluations e LEFT JOIN schools s ON e.school_id = s.id $where", $params)['c'];
$pager = paginate($total, $page, 10);
$list = queryAll("SELECT e.*, s.school_name FROM evaluations e LEFT JOIN schools s ON e.school_id = s.id $where ORDER BY e.evaluation_date DESC LIMIT {$pager['limit']} OFFSET {$pager['offset']}", $params);

include 'header.php';
?>

<?php if ($action == 'add' || $action == 'edit'): ?>
<div class="card">
    <div class="card-header"><?php echo $action == 'edit' ? '编辑评价记录' : '添加评价记录'; ?></div>
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
            <div class="col-md-6"><label class="form-label">评价日期 *</label><input type="date" name="evaluation_date" class="form-control" value="<?php echo e($editData['evaluation_date'] ?? date('Y-m-d')); ?>" required></div>
            <div class="col-md-4"><label class="form-label">评价者类型</label>
                <select name="evaluator_type" class="form-select">
                    <option value="student" <?php echo ($editData['evaluator_type'] ?? 'student') == 'student' ? 'selected' : ''; ?>>学生</option>
                    <option value="teacher" <?php echo ($editData['evaluator_type'] ?? '') == 'teacher' ? 'selected' : ''; ?>>教师</option>
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">评分(0-100)</label><input type="number" step="0.01" max="100" name="score" class="form-control" value="<?php echo $editData['score'] ?? 0; ?>"></div>
            <div class="col-12"><label class="form-label">评价内容</label><textarea name="content" class="form-control" rows="3"><?php echo e($editData['content'] ?? ''); ?></textarea></div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">保存</button>
                <a href="evaluations.php" class="btn btn-secondary">返回</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>评价记录列表</span>
        <div class="d-flex gap-2">
            <form class="d-flex gap-2" method="get">
                <input type="text" name="keyword" class="form-control form-control-sm" placeholder="搜索学校/内容" value="<?php echo e($keyword); ?>">
                <button type="submit" class="btn btn-sm btn-outline-primary">搜索</button>
            </form>
            <a href="evaluations.php?action=add" class="btn btn-sm btn-primary">+ 添加评价</a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>ID</th><th>学校</th><th>日期</th><th>类型</th><th>评分</th><th>内容</th><th>操作</th></tr></thead>
            <tbody>
            <?php foreach ($list as $item): ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo e($item['school_name']); ?></td>
                    <td><?php echo e($item['evaluation_date']); ?></td>
                    <td><span class="badge bg-<?php echo $item['evaluator_type'] == 'student' ? 'info' : 'primary'; ?>"><?php echo $item['evaluator_type'] == 'student' ? '学生' : '教师'; ?></span></td>
                    <td><?php echo $item['score']; ?></td>
                    <td><?php echo e(mb_substr($item['content'] ?? '', 0, 20)); ?></td>
                    <td>
                        <a href="evaluations.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary">编辑</a>
                        <a href="evaluations.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger btn-delete">删除</a>
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