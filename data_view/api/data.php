<?php
/**
 * 大屏数据统一接口
 * 通过 type 参数获取不同板块数据
 * 例: data.php?type=daily_control
 */
require_once __DIR__ . '/../includes/db.php';

$type = $_GET['type'] ?? '';

try {
    switch ($type) {
        // 1. 日管控情况汇总
        case 'daily_control':
            $data = queryOne("SELECT
                SUM(total_units) as total_units,
                SUM(qualified_units) as qualified_units,
                SUM(yellow_line_issues) as yellow_line_issues,
                SUM(basic_issues) as basic_issues
                FROM daily_controls WHERE control_date = CURDATE()");
            $data = $data ?: ['total_units' => 0, 'qualified_units' => 0, 'yellow_line_issues' => 0, 'basic_issues' => 0];
            // 时段分布（最近7天）
            $trend = queryAll("SELECT control_date as timeRange, total_units as totalCount, qualified_units as completedCount, yellow_line_issues as pendingRectificationCount
                FROM daily_controls ORDER BY control_date DESC LIMIT 7");
            jsonResponse(true, [
                'summary' => $data,
                'timeSlots' => array_reverse($trend)
            ]);
            break;

        // 2. 采购总成本分析
        case 'purchase_cost':
            $items = queryAll("SELECT
                ic.category_name as name,
                SUM(pi.amount) as amount,
                ROUND(SUM(pi.amount) / (SELECT SUM(amount) FROM purchase_items) * 100, 2) as rate
                FROM purchase_items pi
                JOIN ingredients i ON pi.ingredient_id = i.id
                JOIN ingredient_categories ic ON i.category_id = ic.id
                GROUP BY ic.id, ic.category_name");
            // 经费数据
            $fund = queryOne("SELECT
                SUM(CASE WHEN status = 0 THEN total_amount ELSE 0 END) as pending,
                SUM(CASE WHEN status = 1 THEN total_amount ELSE 0 END) as paid,
                SUM(total_amount) as total
                FROM purchases");
            jsonResponse(true, [
                'items' => $items,
                'fund' => $fund
            ]);
            break;

        // 3. 膳食经费数据分析
        case 'meal_fund':
            $months = [];
            $series = [];
            for ($i = 5; $i >= 0; $i--) {
                $months[] = date('Y-m', strtotime("-$i month"));
            }
            $fundData = queryAll("SELECT DATE_FORMAT(purchase_date, '%Y-%m') as month, SUM(total_amount) as amount
                FROM purchases GROUP BY DATE_FORMAT(purchase_date, '%Y-%m') ORDER BY month DESC LIMIT 6");
            $fundMap = [];
            foreach ($fundData as $row) $fundMap[$row['month']] = $row['amount'];
            $series[] = [
                'name' => '采购金额',
                'data' => array_map(fn($m) => floatval($fundMap[$m] ?? 0), $months)
            ];
            jsonResponse(true, ['timeAxis' => $months, 'series' => $series]);
            break;

        // 4. 订餐数据分析
        case 'order_analysis':
            $items = queryAll("SELECT s.region as name, SUM(o.total_count) as value
                FROM orders o JOIN schools s ON o.school_id = s.id
                GROUP BY s.region ORDER BY value DESC LIMIT 4");
            jsonResponse(true, ['items' => $items]);
            break;

        // 5. 区域数据分布
        case 'region_distribution':
            $summaryList = queryAll("SELECT region as name, COUNT(*) as num FROM schools GROUP BY region");
            $mapData = queryAll("SELECT s.region as regionName, SUM(o.total_count) as value
                FROM orders o JOIN schools s ON o.school_id = s.id GROUP BY s.region");
            jsonResponse(true, [
                'summaryList' => $summaryList,
                'mapData' => $mapData
            ]);
            break;

        // 6. 食材单价波动分析
        case 'price_trend':
            $timeAxis = [];
            for ($i = 6; $i >= 0; $i--) {
                $timeAxis[] = date('m-d', strtotime("-$i day"));
            }
            $ingredients = queryAll("SELECT id, ingredient_name as name FROM ingredients LIMIT 3");
            $series = [];
            foreach ($ingredients as $ing) {
                $prices = queryAll("SELECT DATE_FORMAT(record_date, '%m-%d') as d, price
                    FROM price_records WHERE ingredient_id = ? AND record_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                    ORDER BY record_date", [$ing['id']]);
                $priceMap = [];
                foreach ($prices as $p) $priceMap[$p['d']] = floatval($p['price']);
                $data = [];
                foreach ($timeAxis as $ta) {
                    $data[] = floatval($priceMap[$ta] ?? 0);
                }
                $series[] = ['name' => $ing['name'], 'data' => $data];
            }
            jsonResponse(true, ['timeAxis' => $timeAxis, 'series' => $series]);
            break;

        // 7. 学生营养情况分析
        case 'nutrition':
            $categories = queryAll("SELECT category_name FROM ingredient_categories ORDER BY sort_order");
            $categoryNames = array_column($categories, 'category_name');
            $regions = queryAll("SELECT DISTINCT region FROM schools LIMIT 4");
            $series = [];
            foreach ($categories as $cat) {
                $data = [];
                foreach ($regions as $r) {
                    $val = queryOne("SELECT SUM(o.total_count) as c
                        FROM orders o JOIN schools s ON o.school_id = s.id
                        WHERE o.ingredient_category = ? AND s.region = ?", [$cat['category_name'], $r['region']]);
                    $data[] = intval($val['c'] ?? 0);
                }
                $series[] = ['name' => $cat['category_name'], 'data' => $data];
            }
            jsonResponse(true, [
                'categoryNames' => array_column($regions, 'region'),
                'series' => $series
            ]);
            break;

        // 8. 供应商评分分析
        case 'supplier_score':
            $suppliers = queryAll("SELECT supplier_name as name, score, grade
                FROM suppliers WHERE status = 1 ORDER BY score DESC LIMIT 5");
            jsonResponse(true, ['suppliers' => $suppliers]);
            break;

        // 9. 月调度情况汇总
        case 'monthly_dispatch':
            $items = queryAll("SELECT id, dispatch_name as name, month, status,
                DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:%s') as time
                FROM monthly_dispatches ORDER BY month DESC LIMIT 12");
            jsonResponse(true, ['items' => $items]);
            break;

        // 10. 周排查情况汇总
        case 'weekly_inspection':
            $data = queryOne("SELECT
                SUM(total_units) as total_checked,
                SUM(qualified_units) as qualified_count,
                SUM(yellow_line_issues) as yellow_issues,
                SUM(basic_issues) as basic_issues
                FROM daily_controls WHERE control_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
            $cards = [
                ['name' => '总计排查单位', 'count' => intval($data['total_checked'] ?? 0)],
                ['name' => '合格单位', 'count' => intval($data['qualified_count'] ?? 0)],
                ['name' => '黄线问题', 'count' => intval($data['yellow_issues'] ?? 0)],
                ['name' => '基础问题', 'count' => intval($data['basic_issues'] ?? 0)],
            ];
            jsonResponse(true, ['cards' => $cards]);
            break;

        // 11. 消费数据分析
        case 'consumption':
            $months = [];
            for ($i = 3; $i >= 0; $i--) {
                $months[] = date('Y-m', strtotime("-$i month"));
            }
            $regions = queryAll("SELECT DISTINCT region FROM schools LIMIT 4");
            $series = [];
            foreach ($regions as $r) {
                $data = [];
                foreach ($months as $m) {
                    $val = queryOne("SELECT SUM(o.total_amount) as total
                        FROM orders o JOIN schools s ON o.school_id = s.id
                        WHERE s.region = ? AND DATE_FORMAT(o.order_date, '%Y-%m') = ?", [$r['region'], $m]);
                    $data[] = floatval($val['total'] ?? 0);
                }
                $series[] = ['name' => $r['region'], 'data' => $data];
            }
            jsonResponse(true, ['months' => $months, 'series' => $series]);
            break;

        // 12. 食材验收质量分析
        case 'acceptance_quality':
            $pieItems = queryAll("SELECT
                CASE quality_status
                    WHEN 2 THEN '优良' WHEN 1 THEN '合格' ELSE '不合格'
                END as name,
                COUNT(*) as rate FROM ingredient_acceptances GROUP BY quality_status");
            $total = array_sum(array_column($pieItems, 'rate'));
            foreach ($pieItems as &$pi) {
                $pi['rate'] = $total > 0 ? round($pi['rate'] / $total * 100, 2) : 0;
            }
            $listItems = queryAll("SELECT ic.category_name as name, COUNT(*) as count
                FROM ingredient_acceptances ia
                JOIN ingredients i ON ia.ingredient_id = i.id
                JOIN ingredient_categories ic ON i.category_id = ic.id
                GROUP BY ic.id, ic.category_name");
            jsonResponse(true, ['pieItems' => $pieItems, 'listItems' => $listItems]);
            break;

        // 13. 师生评价情况分析
        case 'evaluation':
            $timeAxis = [];
            for ($i = 6; $i >= 0; $i--) {
                $timeAxis[] = date('m-d', strtotime("-$i day"));
            }
            $scores = [];
            $examples = [];
            foreach ($timeAxis as $ta) {
                $year = date('Y');
                $fullDate = "$year-$ta";
                $val = queryOne("SELECT AVG(score) as avg_score, content
                    FROM evaluations WHERE DATE_FORMAT(evaluation_date, '%m-%d') = ?
                    ORDER BY evaluation_date DESC LIMIT 1", [$ta]);
                $scores[] = round(floatval($val['avg_score'] ?? 0), 2);
                $examples[] = $val['content'] ?? '';
            }
            jsonResponse(true, [
                'timeAxis' => $timeAxis,
                'scoreList' => $scores,
                'examples' => $examples
            ]);
            break;

        default:
            jsonResponse(false, null, '未知的数据类型: ' . $type);
    }
} catch (Exception $e) {
    jsonResponse(false, null, $e->getMessage());
}
