<?php
require_once __DIR__ . '/../includes/functions.php';
$_SESSION = [];
session_destroy();
alert('已退出登录');
redirect('login.php');
