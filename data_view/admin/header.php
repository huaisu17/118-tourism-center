<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? e($pageTitle) . ' - ' : ''; ?>校园食堂智慧数据管理系统</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f5f6fa; }
        .sidebar { min-height: 100vh; background: #2c3e50; color: #fff; width: 240px; position: fixed; left: 0; top: 0; }
        .sidebar .brand { padding: 20px; font-size: 18px; font-weight: bold; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 12px 20px; border-left: 3px solid transparent; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,0.1); border-left-color: #3498db; }
        .sidebar .nav-link i { width: 24px; }
        .main-content { margin-left: 240px; padding: 20px; }
        .topbar { background: #fff; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
        .card { border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .card-header { background: #fff; border-bottom: 1px solid #f0f0f0; font-weight: 600; padding: 15px 20px; }
        .table th { background: #f8f9fa; font-weight: 600; }
        .btn-sm { padding: 4px 10px; font-size: 13px; }
        .badge { font-weight: 500; padding: 5px 10px; }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="brand"><i class="bi bi-speedometer2"></i> 智慧食堂管理</div>
    <nav class="nav flex-column mt-3">
        <a class="nav-link <?php echo $activeMenu == 'dashboard' ? 'active' : ''; ?>" href="index.php"><i class="bi bi-house-door"></i> 仪表盘</a>
        <a class="nav-link <?php echo $activeMenu == 'schools' ? 'active' : ''; ?>" href="schools.php"><i class="bi bi-building"></i> 学校管理</a>
        <a class="nav-link <?php echo $activeMenu == 'suppliers' ? 'active' : ''; ?>" href="suppliers.php"><i class="bi bi-truck"></i> 供应商管理</a>
        <a class="nav-link <?php echo $activeMenu == 'ingredients' ? 'active' : ''; ?>" href="ingredients.php"><i class="bi bi-basket"></i> 食材管理</a>
        <a class="nav-link <?php echo $activeMenu == 'purchases' ? 'active' : ''; ?>" href="purchases.php"><i class="bi bi-cart"></i> 采购管理</a>
        <a class="nav-link <?php echo $activeMenu == 'orders' ? 'active' : ''; ?>" href="orders.php"><i class="bi bi-calendar-check"></i> 订餐管理</a>
        <a class="nav-link <?php echo $activeMenu == 'daily_controls' ? 'active' : ''; ?>" href="daily_controls.php"><i class="bi bi-shield-check"></i> 日管控管理</a>
        <a class="nav-link <?php echo $activeMenu == 'acceptances' ? 'active' : ''; ?>" href="acceptances.php"><i class="bi bi-clipboard-check"></i> 验收管理</a>
        <a class="nav-link <?php echo $activeMenu == 'evaluations' ? 'active' : ''; ?>" href="evaluations.php"><i class="bi bi-star"></i> 评价管理</a>
        <a class="nav-link <?php echo $activeMenu == 'dispatches' ? 'active' : ''; ?>" href="dispatches.php"><i class="bi bi-calendar-event"></i> 调度管理</a>
        <a class="nav-link <?php echo $activeMenu == 'prices' ? 'active' : ''; ?>" href="prices.php"><i class="bi bi-graph-up"></i> 价格管理</a>
        <?php if (isAdmin()): ?>
        <a class="nav-link <?php echo $activeMenu == 'users' ? 'active' : ''; ?>" href="users.php"><i class="bi bi-people"></i> 用户管理</a>
        <?php endif; ?>
        <a class="nav-link" href="../dashboard/index.html" target="_blank"><i class="bi bi-tv"></i> 数据大屏</a>
        <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> 退出登录</a>
    </nav>
</div>
<div class="main-content">
    <div class="topbar">
        <h5 class="m-0"><?php echo isset($pageTitle) ? e($pageTitle) : '管理后台'; ?></h5>
        <div>
            <span class="text-muted"><?php echo e($user['real_name']); ?></span>
            <span class="badge bg-<?php echo $user['role'] == 0 ? 'danger' : 'primary'; ?> ms-2"><?php echo $user['role'] == 0 ? '管理员' : '普通用户'; ?></span>
        </div>
    </div>
    <?php $flash = getFlash(); if ($flash): ?>
    <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'danger' : $flash['type']; ?> alert-dismissible fade show">
        <?php echo e($flash['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
