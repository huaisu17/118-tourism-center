<?php
/**
 * 数据库配置文件
 * phpStudy 默认配置，根据实际情况修改
 */
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'campus_canteen_db');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_CHARSET', 'utf8mb4');

// 时区设置
date_default_timezone_set('Asia/Shanghai');

// 会话启动
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
