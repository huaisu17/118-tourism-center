<?php
require_once __DIR__ . '/../includes/db.php';
header('Content-Type: application/javascript; charset=utf-8');

// 1. 日管控情况汇总数据 (DataCenter)
$dc = queryOne("SELECT
    SUM(total_units) as total,
    SUM(qualified_units) as qualified,
    SUM(yellow_line_issues) as yellow,
    SUM(basic_issues) as basic
    FROM daily_controls WHERE control_date = CURDATE()") ?: ['total'=>0,'qualified'=>0,'yellow'=>0,'basic'=>0];
if ($dc['total'] == 0) {
    // 如果没有今天的数据，取最新一天
    $dc = queryOne("SELECT
        SUM(total_units) as total,
        SUM(qualified_units) as qualified,
        SUM(yellow_line_issues) as yellow,
        SUM(basic_issues) as basic
        FROM daily_controls WHERE control_date = (SELECT MAX(control_date) FROM daily_controls)") ?: ['total'=>309,'qualified'=>300,'yellow'=>21,'basic'=>234];
}

// 2. 区域产能数据 (ChanNeng)
$cn = queryAll("SELECT s.region as name, SUM(o.total_count) as num
    FROM orders o JOIN schools s ON o.school_id = s.id
    GROUP BY s.region ORDER BY num DESC LIMIT 5");
if (empty($cn)) {
    $cn = [['name'=>'01县','num'=>891433],['name'=>'02县','num'=>189472],['name'=>'03县','num'=>63803]];
}

// 3. 月调度滚动数据 (RZstatus)
$rz = queryAll("SELECT dispatch_name FROM monthly_dispatches ORDER BY month DESC LIMIT 12");
$rzstatus = array_column($rz, 'dispatch_name');
if (empty($rzstatus)) {
    $rzstatus = ['一月月调度情况','二月月调度情况','三月月调度情况','四月月调度情况','五月月调度情况','六月月调度情况',
        '七月月调度情况','八月月调度情况','九月月调度情况','十月月调度情况','十一月月调度情况','十二月月调度情况'];
}

// 输出 JavaScript 变量
echo "var DataCenter = [";
echo "{name: '总计排查单位', num: '" . ($dc['total'] ?: 309) . "'},";
echo "{name: '合格单位', num: '" . ($dc['qualified'] ?: 300) . "'},";
echo "{name: '黄线问题', num: '" . ($dc['yellow'] ?: 21) . "'},";
echo "{name: '基础问题', num: '" . ($dc['basic'] ?: 234) . "'}";
echo "];\n";

echo "var ChanNeng = [";
$first = true;
foreach ($cn as $row) {
    if (!$first) echo ",";
    echo "{name: '" . addslashes($row['name']) . "订单数', num: " . intval($row['num']) . "}";
    $first = false;
}
echo "];\n";

echo "var RZstatus = [";
$first = true;
foreach ($rzstatus as $v) {
    if (!$first) echo ",";
    echo "'" . addslashes($v) . "'";
    $first = false;
}
echo "];\n";

// 保留原有变量但清空，避免 index.js 报错
echo "var callMsg = ['数据已同步'];\n";
echo "var CJstatus = [[]];\n";
