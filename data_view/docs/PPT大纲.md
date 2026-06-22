# 校园食堂智慧数据管理系统 — 答辩 PPT 详细大纲

> 本大纲包含可直接放入 PPT 的具体内容，不采用"示例"或"节选"形式。
> 建议总时长：6-8 分钟，共 13 页。

---

## 第 1 页：封面

**标题**：校园食堂智慧数据管理系统

**副标题**：数据库课程大作业答辩

**页面内容**：
- 汇报人：XXX
- 小组成员：XXX、XXX、XXX
- 指导老师：XXX
- 日期：2026 年 6 月

**演讲备注**：

大家好，我们小组的题目是《校园食堂智慧数据管理系统》。本次汇报将从项目背景、需求分析、数据库设计、系统实现和总结展望五个方面展开，总时长约 6 分钟。

---

## 第 2 页：目录

**标题**：汇报目录

**页面内容**：

1. 项目背景与系统说明
2. 需求分析
3. 数据库设计
4. 数据库实施
5. 总结与展望

**演讲备注**：

本次汇报共分为五个部分。其中数据库设计 ER 图、数据字典和代码实现是本次汇报的重点内容。

---

## 第 3 页：项目背景

**标题**：项目背景

**页面内容**：

- 高校后勤管理信息化水平不断提升
- 传统人工记录方式效率低、数据分散、统计困难
- 食堂采购、食材验收、供应商评价等业务数据缺乏统一管理
- 管理人员难以实时掌握食堂运营状况
- 需要一套规范化的数据管理系统，实现数据管理的规范化、程序化、科学化

**演讲备注**：

随着高校后勤管理信息化的发展，传统的人工记录方式已经难以满足食堂日常运营管理需求。采购、验收、供应商数据分散，统计困难，因此我们设计并实现了这套校园食堂智慧数据管理系统。

---

## 第 4 页：系统说明

**标题**：系统说明

**页面内容**：

**系统定位**：面向校园食堂后勤管理人员的数据管理系统

**核心价值**：
- 实现食堂基础档案、供应商、食材的统一管理
- 支持采购订单与采购明细的录入、查询、修改、删除
- 支持食材验收记录的管理与质量状态统计
- 满足课程大作业对 Web 应用系统增删改查及现场演示的要求

**用户角色与权限**：

| 角色 | 权限 |
|------|------|
| 系统管理员 | 所有模块的增删改查权限 |
| 普通用户 | 查看数据、录入数据，无删除权限 |

**开发工具**：MySQL 8.0 + Python 3.x + PyMySQL

**演讲备注**：

本系统面向食堂后勤管理人员，采用 MySQL 作为数据库，Python 加 PyMySQL 实现后端交互，区分管理员和普通用户两种角色，满足课程大作业的增删改查要求。

---

## 第 5 页：系统需求

**标题**：系统需求

**页面内容**：

从校园食堂运营管理角度，系统需要满足以下六大核心需求：

| 序号 | 需求模块 | 具体内容 |
|------|----------|----------|
| 1 | 食堂档案管理 | 对食堂基础信息进行维护，包括食堂名称、食堂类型等 |
| 2 | 供应商管理 | 维护供应商信息，记录供应商名称、综合评分等 |
| 3 | 食材分类与食材管理 | 对食材进行分类管理，并维护具体食材信息 |
| 4 | 采购全流程管理 | 记录采购订单及其明细，支持按订单、按食材查询 |
| 5 | 食材验收管理 | 记录每次食材到货后的验收情况，包括验收日期、质量状态等 |
| 6 | 数据统计 | 支持按食堂、供应商、食材等维度进行基础统计 |

**演讲备注**：

系统包含六大核心功能，覆盖食堂档案、供应商、食材、采购、验收和统计，完整体现了课程大作业对数据库增删改查功能的要求。

---

## 第 6 页：数据需求

**标题**：数据需求

**页面内容**：

系统需要对以下六大类核心数据进行增删改查管理：

| 序号 | 数据类型 | 说明 |
|------|----------|------|
| 1 | 食堂信息 | 食堂基础档案数据 |
| 2 | 供应商信息 | 供应商基础档案及评分数据 |
| 3 | 食材分类信息 | 食材分类目录数据 |
| 4 | 食材信息 | 具体食材档案数据 |
| 5 | 采购信息 | 采购订单及采购明细数据 |
| 6 | 食材验收信息 | 食材到货验收记录数据 |

**演讲备注**：

以上六类数据是系统管理的核心对象，它们共同构成了数据库设计的基础，后续将设计为 7 张数据表进行管理。

---

## 第 7 页：数据字典

**标题**：数据字典（核心数据表定义）

**页面内容**：

数据字典定义了系统全部 7 张数据表的含义、组成字段，统一字段数据类型、长度、取值范围，是数据库建表的核心依据。

**表 1：食堂信息表（canteens）**

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 食堂ID |
| canteen_name | VARCHAR(100) | NOT NULL | 食堂名称 |
| canteen_type | VARCHAR(20) | | 食堂类型（自营/外包） |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |

**表 2：供应商信息表（suppliers）**

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 供应商ID |
| supplier_name | VARCHAR(100) | NOT NULL | 供应商名称 |
| score | DECIMAL(4,2) | DEFAULT 0.00 | 综合评分（0-100） |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |

**表 3：食材分类表（ingredient_categories）**

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 分类ID |
| category_name | VARCHAR(50) | NOT NULL | 分类名称 |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |

**表 4：食材信息表（ingredients）**

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 食材ID |
| ingredient_name | VARCHAR(100) | NOT NULL | 食材名称 |
| category_id | INT | FK | 所属分类ID |
| unit | VARCHAR(20) | DEFAULT 'kg' | 计量单位 |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |

**表 5：采购订单表（purchases）**

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 采购ID |
| canteen_id | INT | FK, NOT NULL | 食堂ID |
| supplier_id | INT | FK | 供应商ID |
| purchase_date | DATE | NOT NULL | 采购日期 |
| total_amount | DECIMAL(12,2) | DEFAULT 0.00 | 采购总金额（元） |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |

**表 6：采购明细表（purchase_items）**

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 明细ID |
| purchase_id | INT | FK, NOT NULL | 采购订单ID |
| ingredient_id | INT | FK, NOT NULL | 食材ID |
| quantity | DECIMAL(10,2) | NOT NULL | 采购数量 |
| unit_price | DECIMAL(10,2) | NOT NULL | 单价（元） |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |

**表 7：食材验收表（ingredient_acceptances）**

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 验收ID |
| canteen_id | INT | FK, NOT NULL | 食堂ID |
| ingredient_id | INT | FK, NOT NULL | 食材ID |
| acceptance_date | DATE | NOT NULL | 验收日期 |
| quality_status | TINYINT | DEFAULT 1 | 质量状态：0不合格，1合格，2优良 |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |

**演讲备注**：

数据字典是全文核心数据规范，共定义 7 张数据表，统一字段类型、主键、外键和非空约束，确保数据的一致性和完整性。

---

## 第 8 页：E-R 图设计

**标题**：概念结构设计 — E-R 图

**页面内容**：

基于需求分析搭建系统概念模型，设计 7 张单实体 E-R 图 + 1 张系统整体 E-R 图。

**实体列表**：

| 实体 | 说明 |
|------|------|
| 食堂 | 食堂基础档案 |
| 供应商 | 食材供应商 |
| 食材分类 | 食材分类目录 |
| 食材 | 具体食材 |
| 采购订单 | 采购订单主表 |
| 采购明细 | 采购订单明细 |
| 食材验收 | 食材验收记录 |

**实体间关系**：

| 关系 | 实体A | 基数 | 实体B | 关系属性 |
|------|-------|------|-------|----------|
| 分类 | 食材分类 | 1:n | 食材 | — |
| 采购 | 食堂 | 1:n | 采购订单 | 采购日期 |
| 供货 | 供应商 | 1:n | 采购订单 | 供货日期 |
| 包含 | 采购订单 | 1:n | 采购明细 | 行号 |
| 被采购 | 食材 | 1:n | 采购明细 | 数量、单价 |
| 验收 | 食堂 | 1:n | 食材验收 | 验收日期 |
| 被验收 | 食材 | 1:n | 食材验收 | 质量状态 |

**演讲备注**：

本系统 E-R 图包含 7 个实体，实体间主要以一对多关系关联。采购明细表用于表达采购订单与食材之间的关联，同时保存数量和单价等关系属性。

---

## 第 9 页：逻辑结构设计

**标题**：逻辑结构设计

**页面内容**：

### 关系模型

将 E-R 概念模型转换为关系模型，得到以下 7 个关系：

1. **食堂**（食堂ID，食堂名称，食堂类型）
2. **供应商**（供应商ID，供应商名称，综合评分）
3. **食材分类**（分类ID，分类名称）
4. **食材**（食材ID，分类ID，食材名称，计量单位）
5. **采购订单**（采购ID，食堂ID，供应商ID，采购日期，总金额）
6. **采购明细**（明细ID，采购ID，食材ID，数量，单价）
7. **食材验收**（验收ID，食堂ID，食材ID，验收日期，质量状态）

### 细化表结构

| 表名 | 字段名 | 数据类型 | 主键 | 外键 | 非空 | 说明 |
|------|--------|----------|------|------|------|------|
| canteens | id | INT | PK | — | 是 | 食堂ID |
| canteens | canteen_name | VARCHAR(100) | — | — | 是 | 食堂名称 |
| canteens | canteen_type | VARCHAR(20) | — | — | 否 | 食堂类型 |
| suppliers | id | INT | PK | — | 是 | 供应商ID |
| suppliers | supplier_name | VARCHAR(100) | — | — | 是 | 供应商名称 |
| suppliers | score | DECIMAL(4,2) | — | — | 否 | 综合评分 |
| ingredient_categories | id | INT | PK | — | 是 | 分类ID |
| ingredient_categories | category_name | VARCHAR(50) | — | — | 是 | 分类名称 |
| ingredients | id | INT | PK | — | 是 | 食材ID |
| ingredients | ingredient_name | VARCHAR(100) | — | — | 是 | 食材名称 |
| ingredients | category_id | INT | — | FK | 否 | 分类ID |
| ingredients | unit | VARCHAR(20) | — | — | 否 | 计量单位 |
| purchases | id | INT | PK | — | 是 | 采购ID |
| purchases | canteen_id | INT | — | FK | 是 | 食堂ID |
| purchases | supplier_id | INT | — | FK | 否 | 供应商ID |
| purchases | purchase_date | DATE | — | — | 是 | 采购日期 |
| purchases | total_amount | DECIMAL(12,2) | — | — | 否 | 总金额 |
| purchase_items | id | INT | PK | — | 是 | 明细ID |
| purchase_items | purchase_id | INT | — | FK | 是 | 采购订单ID |
| purchase_items | ingredient_id | INT | — | FK | 是 | 食材ID |
| purchase_items | quantity | DECIMAL(10,2) | — | — | 是 | 数量 |
| purchase_items | unit_price | DECIMAL(10,2) | — | — | 是 | 单价 |
| ingredient_acceptances | id | INT | PK | — | 是 | 验收ID |
| ingredient_acceptances | canteen_id | INT | — | FK | 是 | 食堂ID |
| ingredient_acceptances | ingredient_id | INT | — | FK | 是 | 食材ID |
| ingredient_acceptances | acceptance_date | DATE | — | — | 是 | 验收日期 |
| ingredient_acceptances | quality_status | TINYINT | — | — | 否 | 质量状态 |

**演讲备注**：

将 E-R 图转换为关系模型后，得到 7 个关系。细化表结构明确了英文字段名、数据类型和主外键约束，为 SQL 建表提供标准规范，设计遵循第三范式，避免数据冗余。

---

## 第 10 页：数据库建表语句

**标题**：数据库实施 — SQL 建表

**页面内容**：

```sql
-- 创建数据库
CREATE DATABASE IF NOT EXISTS campus_canteen 
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE campus_canteen;

-- 1. 食堂信息表
CREATE TABLE canteens (
    id INT PRIMARY KEY AUTO_INCREMENT COMMENT '食堂ID',
    canteen_name VARCHAR(100) NOT NULL COMMENT '食堂名称',
    canteen_type VARCHAR(20) COMMENT '食堂类型（自营/外包）',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) COMMENT='食堂信息表';

-- 2. 供应商信息表
CREATE TABLE suppliers (
    id INT PRIMARY KEY AUTO_INCREMENT COMMENT '供应商ID',
    supplier_name VARCHAR(100) NOT NULL COMMENT '供应商名称',
    score DECIMAL(4,2) DEFAULT 0.00 COMMENT '综合评分（0-100）',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) COMMENT='供应商信息表';

-- 3. 食材分类表
CREATE TABLE ingredient_categories (
    id INT PRIMARY KEY AUTO_INCREMENT COMMENT '分类ID',
    category_name VARCHAR(50) NOT NULL COMMENT '分类名称',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) COMMENT='食材分类表';

-- 4. 食材信息表
CREATE TABLE ingredients (
    id INT PRIMARY KEY AUTO_INCREMENT COMMENT '食材ID',
    ingredient_name VARCHAR(100) NOT NULL COMMENT '食材名称',
    category_id INT COMMENT '所属分类ID',
    unit VARCHAR(20) DEFAULT 'kg' COMMENT '计量单位',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    FOREIGN KEY (category_id) REFERENCES ingredient_categories(id)
) COMMENT='食材信息表';

-- 5. 采购订单表
CREATE TABLE purchases (
    id INT PRIMARY KEY AUTO_INCREMENT COMMENT '采购ID',
    canteen_id INT NOT NULL COMMENT '食堂ID',
    supplier_id INT COMMENT '供应商ID',
    purchase_date DATE NOT NULL COMMENT '采购日期',
    total_amount DECIMAL(12,2) DEFAULT 0.00 COMMENT '采购总金额（元）',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    FOREIGN KEY (canteen_id) REFERENCES canteens(id),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
) COMMENT='采购订单表';

-- 6. 采购明细表
CREATE TABLE purchase_items (
    id INT PRIMARY KEY AUTO_INCREMENT COMMENT '明细ID',
    purchase_id INT NOT NULL COMMENT '采购订单ID',
    ingredient_id INT NOT NULL COMMENT '食材ID',
    quantity DECIMAL(10,2) NOT NULL COMMENT '采购数量',
    unit_price DECIMAL(10,2) NOT NULL COMMENT '单价（元）',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    FOREIGN KEY (purchase_id) REFERENCES purchases(id),
    FOREIGN KEY (ingredient_id) REFERENCES ingredients(id)
) COMMENT='采购明细表';

-- 7. 食材验收表
CREATE TABLE ingredient_acceptances (
    id INT PRIMARY KEY AUTO_INCREMENT COMMENT '验收ID',
    canteen_id INT NOT NULL COMMENT '食堂ID',
    ingredient_id INT NOT NULL COMMENT '食材ID',
    acceptance_date DATE NOT NULL COMMENT '验收日期',
    quality_status TINYINT DEFAULT 1 COMMENT '质量状态：0不合格，1合格，2优良',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    FOREIGN KEY (canteen_id) REFERENCES canteens(id),
    FOREIGN KEY (ingredient_id) REFERENCES ingredients(id)
) COMMENT='食材验收表';
```

**演讲备注**：

以上 SQL 语句完成了数据库和 7 张数据表的创建，包含主键、外键和非空约束，使用 utf8mb4 字符集，确保数据完整性和中文支持。

---

## 第 11 页：系统实现（一）后端

**标题**：系统实现 — 后端架构与核心代码

**页面内容**：

### 项目技术架构

```
┌─────────────────────────────────────────────────────────┐
│  前端层                                                   │
│  ├── 数据大屏：index.html + jQuery + ECharts 4.x        │
│  └── 管理后台：admin/ 目录下的 PHP 页面（Bootstrap）     │
├─────────────────────────────────────────────────────────┤
│  后端层                                                   │
│  ├── 公共库：includes/db.php、includes/functions.php    │
│  ├── 大屏接口：api/data.php                              │
│  └── 后台处理：admin/*.php                               │
├─────────────────────────────────────────────────────────┤
│  数据层                                                   │
│  └── MySQL 8.0：campus_canteen_db 数据库                 │
└─────────────────────────────────────────────────────────┘
```

### 数据库连接公共库 includes/db.php

```php
<?php
require_once __DIR__ . '/config.php';

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT
             . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
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

function queryAll($sql, $params = []) {
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function queryOne($sql, $params = []) {
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result ?: null;
}

function execute($sql, $params = []) {
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
}
```

### 大屏数据接口 api/data.php（以日管控为例）

```php
<?php
require_once __DIR__ . '/../includes/db.php';

$type = $_GET['type'] ?? '';

switch ($type) {
    case 'daily_control':
        $data = queryOne("SELECT
            SUM(total_units) as total_units,
            SUM(qualified_units) as qualified_units,
            SUM(yellow_line_issues) as yellow_line_issues,
            SUM(basic_issues) as basic_issues
            FROM daily_controls WHERE control_date = CURDATE()");
        $data = $data ?: ['total_units' => 0, 'qualified_units' => 0,
                          'yellow_line_issues' => 0, 'basic_issues' => 0];
        $trend = queryAll("SELECT control_date as timeRange,
            total_units as totalCount,
            qualified_units as completedCount,
            yellow_line_issues as pendingRectificationCount
            FROM daily_controls ORDER BY control_date DESC LIMIT 7");
        jsonResponse(true, [
            'summary' => $data,
            'timeSlots' => array_reverse($trend)
        ]);
        break;
}
```

### 管理后台增删改查 admin/ingredients.php（节选）

```php
// 删除食材
if ($action == 'delete' && $id > 0) {
    execute("DELETE FROM ingredients WHERE id = ?", [$id]);
    alert('删除成功');
    redirect('ingredients.php');
}

// 保存食材
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'ingredient_name' => trim(input('ingredient_name')),
        'category_id' => intval(input('category_id')),
        'unit' => trim(input('unit'))
    ];
    if ($id > 0) {
        execute("UPDATE ingredients SET ingredient_name=?, category_id=?, unit=? WHERE id=?",
            [$data['ingredient_name'], $data['category_id'], $data['unit'], $id]);
        alert('更新成功');
    } else {
        execute("INSERT INTO ingredients (ingredient_name, category_id, unit) VALUES (?,?,?)",
            array_values($data));
        alert('添加成功');
    }
    redirect('ingredients.php');
}

// 查询食材列表（带分类）
$list = queryAll("SELECT i.*, c.category_name
    FROM ingredients i
    LEFT JOIN ingredient_categories c ON i.category_id = c.id
    ORDER BY i.id DESC LIMIT {$pager['limit']} OFFSET {$pager['offset']}", $params);
```

**演讲备注**：

系统采用 PHP + MySQL 实现真正的前后端分离架构。后端通过 `includes/db.php` 统一使用 PDO 连接数据库，大屏数据由 `api/data.php` 统一接口提供，管理后台的增删改查由 `admin/` 目录下的 PHP 页面处理。所有数据库操作均使用预处理语句，有效防止 SQL 注入。

---

## 第 12 页：系统实现（二）前端

**标题**：系统实现 — 前端大屏与管理后台

**页面内容**：

### 数据大屏前端 index.html

- 基于 HTML5 + CSS3 构建 3840×2160 4K 固定布局
- 使用 jQuery 处理交互逻辑
- 使用 ECharts 4.x 绘制各类图表（柱状图、饼图、折线图、地图等）
- 目前通过 `js/data.js` 提供静态演示数据，预留 `api/data.php` 接口用于后续动态数据对接

### 大屏核心代码结构

```html
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>智校数据综合展示中心</title>
    <link rel="stylesheet" href="cs/index.css">
    <script src="js/jquery.js"></script>
    <script src="js/echarts.min.js"></script>
    <script src="js/data.js"></script>
</head>
<body>
    <div class="content">
        <div id="head">标题/时间/天气</div>
        <div id="body">
            <div class="bodyLeft">左侧图表区</div>
            <div class="bodyMiddle">中间地图区</div>
            <div class="bodyRight">右侧图表区</div>
        </div>
        <div id="foot">底部图表区</div>
    </div>
    <script src="js/index.js"></script>
</body>
</html>
```

### 管理后台前端 admin/ingredients.php（表单与列表）

```html
<form method="post" class="row g-3">
    <div class="col-md-6">
        <label class="form-label">食材名称 *</label>
        <input type="text" name="ingredient_name" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">所属分类 *</label>
        <select name="category_id" class="form-select" required>
            <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['category_name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">计量单位</label>
        <input type="text" name="unit" class="form-control" value="kg">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">保存</button>
    </div>
</form>
```

### 已实现功能

| 模块 | 功能 |
|------|------|
| 大屏展示 | 13 个数据板块的可视化展示 |
| 用户管理 | 登录、登出、权限控制 |
| 学校管理 | 学校信息的增删改查 |
| 供应商管理 | 供应商信息的增删改查 |
| 食材管理 | 食材分类和食材的增删改查 |
| 采购管理 | 采购订单及明细的增删改查 |
| 验收管理 | 食材验收记录的增删改查 |
| 其他模块 | 订餐、日管控、月调度、评价、价格等 |

**演讲备注**：

前端分为数据大屏和管理后台两部分。大屏采用 4K 固定布局，使用 ECharts 绘制多种图表；管理后台使用 Bootstrap 构建，提供友好的表单和列表界面，完整实现了系统各模块的增删改查功能。

---

## 第 13 页：总结与展望

**标题**：总结与展望

**页面内容**：

### 设计亮点

- **完整的前后端架构**：PHP 后端 + HTML/jQuery/ECharts 前端 + MySQL 数据库
- **数据库设计规范**：13 张表覆盖食堂业务全链条，主外键约束完整
- **数据可视化大屏**：13 个数据板块的 4K 大屏展示
- **管理后台完善**：提供各模块的增删改查、搜索、分页功能
- **安全性考虑**：使用 PDO 预处理语句，有效防止 SQL 注入

### 不足之处

- 大屏目前使用静态演示数据，尚未完全接入后端动态接口
- 部分前端交互和异常提示可以进一步优化
- 缺少更完善的用户权限细粒度控制

### 改进方向

- 将大屏数据完全接入 `api/data.php` 动态接口
- 增加数据导出、批量操作等高级功能
- 优化前端响应式布局，支持更多分辨率
- 增加操作日志和系统监控功能

**演讲备注**：

总结来说，本系统完成了课程大作业的核心要求，具备完整的前后端架构、规范的数据库设计和数据可视化大屏。未来可以进一步完善动态数据对接和前端交互体验。

---

## 第 14 页：谢谢观看

**标题**：谢谢观看

**副标题**：请各位老师批评指正

**页面内容**：
- 汇报人：XXX
- 联系方式：XXX

**演讲备注**：

以上就是我们小组的汇报内容，感谢各位老师的聆听，请批评指正。

---

## 附录：PPT 制作建议

| 项目 | 建议 |
|------|------|
| 总页数 | 14 页 |
| 每页时长 | 25-40 秒 |
| 重点页面 | 第 7 页数据字典、第 8 页 E-R 图、第 9 页逻辑结构、第 11-12 页系统实现 |
| 必放图片 | ER 图、表结构、系统架构图、后端代码截图、大屏截图、后台截图 |
| 配色建议 | 蓝色或绿色系，简洁商务风 |
| 字体建议 | 标题 28-32 号，正文 16-20 号 |

---

## 可直接复制给 Kimi 的精简提示词

```text
请根据以下完整内容生成一份 14 页的数据库课程大作业答辩 PPT，主题为"校园食堂智慧数据管理系统"。

项目技术栈：
- 前端：HTML5 + CSS3 + jQuery + ECharts 4.x + Bootstrap
- 后端：PHP + PDO + MySQL 8.0
- 数据库：campus_canteen_db，包含 users、schools、suppliers、ingredient_categories、ingredients、purchases、purchase_items、orders、daily_controls、ingredient_acceptances、evaluations、monthly_dispatches、price_records 共 13 张表
- 项目结构：
  - 数据大屏：index.html + js/index.js + js/data.js
  - 管理后台：admin/ 目录下 PHP 页面
  - 后端接口：api/data.php
  - 公共库：includes/db.php、includes/functions.php

要求：
1. 严格按以下页码生成：封面、目录、项目背景、系统说明、系统需求、数据需求、数据字典、E-R 图设计、逻辑结构设计、数据库建表语句、后端实现、前端实现、总结与展望、谢谢观看
2. 第 7 页数据字典需包含核心数据表的完整字段定义
3. 第 8 页 E-R 图需包含 7 个核心实体及关系
4. 第 9 页逻辑结构需包含关系模型和细化表结构
5. 第 10 页需包含核心表的 SQL 建表语句
6. 第 11 页需展示 PHP 后端架构、PDO 连接、api/data.php 接口、admin/ingredients.php 增删改查代码
7. 第 12 页需展示前端大屏 index.html 结构和管理后台表单代码
8. 每页给出标题、3-5 个要点、建议配图、演讲备注
9. 输出 Markdown 格式
```
