<?php
require_once __DIR__ . '/config.php';

/**
 * 获取数据库连接（PDO）
 * @return PDO
 */
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("数据库连接失败: " . $e->getMessage());
        }
    }
    return $pdo;
}

/**
 * 执行查询并返回所有结果
 * @param string $sql
 * @param array $params
 * @return array
 */
function queryAll($sql, $params = []) {
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * 执行查询并返回单行
 * @param string $sql
 * @param array $params
 * @return array|null
 */
function queryOne($sql, $params = []) {
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result ?: null;
}

/**
 * 执行插入/更新/删除
 * @param string $sql
 * @param array $params
 * @return int 受影响的行数
 */
function execute($sql, $params = []) {
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
}

/**
 * 获取最后插入的ID
 * @return string
 */
function lastInsertId() {
    return getDB()->lastInsertId();
}
