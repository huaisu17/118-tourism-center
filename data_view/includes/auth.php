<?php
require_once __DIR__ . '/functions.php';

/**
 * 检查是否已登录
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;
}

/**
 * 获取当前登录用户
 * @return array|null
 */
function currentUser() {
    if (!isLoggedIn()) return null;
    return queryOne("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
}

/**
 * 检查是否为管理员
 * @return bool
 */
function isAdmin() {
    $user = currentUser();
    return $user && $user['role'] == 0;
}

/**
 * 要求登录，未登录则跳转
 */
function requireLogin() {
    if (!isLoggedIn()) {
        alert('请先登录', 'warning');
        redirect('login.php');
    }
}

/**
 * 要求管理员权限
 */
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        alert('权限不足', 'error');
        redirect('index.php');
    }
}
