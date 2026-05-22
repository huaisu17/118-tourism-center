<?php
require_once __DIR__ . '/db.php';

/**
 * 安全输出 HTML
 * @param string $text
 * @return string
 */
function e($text) {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * 获取 GET/POST 参数
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function input($key, $default = null) {
    if (isset($_POST[$key])) return $_POST[$key];
    if (isset($_GET[$key])) return $_GET[$key];
    return $default;
}

/**
 * 重定向
 * @param string $url
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * 显示提示消息
 * @param string $message
 * @param string $type success|error|warning
 */
function alert($message, $type = 'success') {
    $_SESSION['flash_message'] = ['message' => $message, 'type' => $type];
}

/**
 * 获取并清除提示消息
 * @return array|null
 */
function getFlash() {
    if (isset($_SESSION['flash_message'])) {
        $msg = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $msg;
    }
    return null;
}

/**
 * 分页计算
 * @param int $total 总记录数
 * @param int $page 当前页
 * @param int $pageSize 每页条数
 * @return array [limit, offset, totalPage]
 */
function paginate($total, $page = 1, $pageSize = 10) {
    $page = max(1, intval($page));
    $pageSize = max(1, min(100, intval($pageSize)));
    $totalPage = max(1, ceil($total / $pageSize));
    $page = min($page, $totalPage);
    $offset = ($page - 1) * $pageSize;
    return [
        'page' => $page,
        'pageSize' => $pageSize,
        'totalPage' => $totalPage,
        'offset' => $offset,
        'limit' => $pageSize
    ];
}

/**
 * 统一 JSON 响应（供 API 使用）
 * @param bool $success
 * @param mixed $data
 * @param string $message
 */
function jsonResponse($success, $data = null, $message = '') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'code' => $success ? 200 : 500,
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'serverTime' => date('Y-m-d H:i:s'),
        'requestId' => uniqid('req_')
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * 验证必填字段
 * @param array $fields
 * @param array $data
 * @return array 错误信息数组
 */
function validateRequired($fields, $data) {
    $errors = [];
    foreach ($fields as $field => $label) {
        if (empty($data[$field]) && $data[$field] !== '0') {
            $errors[] = "$label 不能为空";
        }
    }
    return $errors;
}
