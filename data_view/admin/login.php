<?php
require_once __DIR__ . '/../includes/functions.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(input('username'));
    $password = input('password');

    if (empty($username) || empty($password)) {
        $error = '请输入用户名和密码';
    } else {
        $user = queryOne("SELECT * FROM users WHERE username = ? AND status = 1", [$username]);
        if ($user && $user['password'] === md5($password)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            alert('登录成功');
            redirect('index.php');
        } else {
            $error = '用户名或密码错误';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录 - 校园食堂智慧数据管理系统</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-box { background: #fff; padding: 40px; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); width: 100%; max-width: 400px; }
        .login-box h3 { text-align: center; margin-bottom: 30px; color: #333; }
        .btn-login { width: 100%; padding: 12px; background: #667eea; border: none; }
        .btn-login:hover { background: #5568d3; }
    </style>
</head>
<body>
<div class="login-box">
    <h3><i class="bi bi-shield-lock"></i> 系统登录</h3>
    <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo e($error); ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">用户名</label>
            <input type="text" name="username" class="form-control" placeholder="请输入用户名" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">密码</label>
            <input type="password" name="password" class="form-control" placeholder="请输入密码" required>
        </div>
        <button type="submit" class="btn btn-primary btn-login">登 录</button>
        <div class="mt-3 text-center text-muted small">
            默认管理员: admin / admin123
        </div>
    </form>
</div>
</body>
</html>
