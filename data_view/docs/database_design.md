# 校园食堂智慧数据管理系统 — 数据库设计文档

## 1. 设计概述

数据库采用 MySQL 8.0，存储引擎 InnoDB，字符集 utf8mb4。设计遵循第三范式（3NF），确保数据无冗余、关联清晰。

## 2. ER 图说明

```
users (1) ──┐
schools (1) ─┼──< orders (N)
             ├──< daily_controls (N)
             ├──< ingredient_acceptances (N)
             ├──< evaluations (N)
             ├──< monthly_dispatches (N)
             ├──< purchases (N) >──(1) suppliers
             │                      └──< purchase_items (N) >──(1) ingredients
             │                                                    └──(1) ingredient_categories
             └──< price_records (N) >──(1) ingredients
```

## 3. 表结构设计

### 3.1 users（用户表）

存储系统登录用户信息。

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 用户ID |
| username | VARCHAR(50) | NOT NULL, UNIQUE | 登录用户名 |
| password | VARCHAR(64) | NOT NULL | MD5加密密码 |
| real_name | VARCHAR(50) | NOT NULL | 真实姓名 |
| role | TINYINT | NOT NULL, DEFAULT 1 | 角色：0管理员，1普通用户 |
| status | TINYINT | NOT NULL, DEFAULT 1 | 状态：0禁用，1启用 |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |
| updated_at | DATETIME | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | 更新时间 |

### 3.2 schools（学校表）

存储学校/区域基本信息。

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 学校ID |
| school_name | VARCHAR(100) | NOT NULL | 学校名称 |
| region | VARCHAR(50) | NOT NULL | 所属区域/县区 |
| address | VARCHAR(200) | | 详细地址 |
| contact_person | VARCHAR(50) | | 联系人 |
| contact_phone | VARCHAR(20) | | 联系电话 |
| student_count | INT | DEFAULT 0 | 学生人数 |
| teacher_count | INT | DEFAULT 0 | 教师人数 |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |
| updated_at | DATETIME | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | 更新时间 |

### 3.3 suppliers（供应商表）

存储食材供应商信息。

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 供应商ID |
| supplier_name | VARCHAR(100) | NOT NULL | 供应商名称 |
| contact_person | VARCHAR(50) | | 联系人 |
| contact_phone | VARCHAR(20) | | 联系电话 |
| address | VARCHAR(200) | | 地址 |
| score | DECIMAL(4,2) | DEFAULT 0.00 | 综合评分（0-100） |
| grade | VARCHAR(10) | | 评级（A/B/C/D/E） |
| status | TINYINT | DEFAULT 1 | 状态：0停用，1合作中 |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |
| updated_at | DATETIME | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | 更新时间 |

### 3.4 ingredient_categories（食材分类表）

存储食材分类信息。

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 分类ID |
| category_name | VARCHAR(50) | NOT NULL | 分类名称 |
| sort_order | INT | DEFAULT 0 | 排序号 |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |

### 3.5 ingredients（食材表）

存储具体食材信息。

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 食材ID |
| ingredient_name | VARCHAR(100) | NOT NULL | 食材名称 |
| category_id | INT | FK | 所属分类ID |
| unit | VARCHAR(20) | DEFAULT 'kg' | 计量单位 |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |

### 3.6 purchases（采购记录表）

存储采购订单主信息。

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 采购ID |
| school_id | INT | FK, NOT NULL | 学校ID |
| supplier_id | INT | FK | 供应商ID |
| purchase_date | DATE | NOT NULL | 采购日期 |
| total_amount | DECIMAL(12,2) | DEFAULT 0.00 | 采购总金额（元） |
| status | TINYINT | DEFAULT 1 | 状态：0待支付，1已支付 |
| remark | VARCHAR(500) | | 备注 |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |
| updated_at | DATETIME | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | 更新时间 |

### 3.7 purchase_items（采购明细表）

存储采购订单明细。

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 明细ID |
| purchase_id | INT | FK, NOT NULL | 采购订单ID |
| ingredient_id | INT | FK, NOT NULL | 食材ID |
| quantity | DECIMAL(10,2) | NOT NULL | 采购数量 |
| unit_price | DECIMAL(10,2) | NOT NULL | 单价（元） |
| amount | DECIMAL(12,2) | NOT NULL | 金额（元） |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |

### 3.8 orders（订餐记录表）

存储每日订餐数据。

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 记录ID |
| school_id | INT | FK, NOT NULL | 学校ID |
| order_date | DATE | NOT NULL | 订餐日期 |
| student_count | INT | DEFAULT 0 | 学生订餐数 |
| teacher_count | INT | DEFAULT 0 | 教师订餐数 |
| total_count | INT | DEFAULT 0 | 总订餐数 |
| total_amount | DECIMAL(12,2) | DEFAULT 0.00 | 订餐总金额（元） |
| ingredient_category | VARCHAR(50) | | 主要营养类别 |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |
| updated_at | DATETIME | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | 更新时间 |

### 3.9 daily_controls（日管控记录表）

存储每日管控排查数据。

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 记录ID |
| school_id | INT | FK, NOT NULL | 学校ID |
| control_date | DATE | NOT NULL | 管控日期 |
| total_units | INT | DEFAULT 0 | 排查单位总数 |
| qualified_units | INT | DEFAULT 0 | 合格单位数 |
| yellow_line_issues | INT | DEFAULT 0 | 黄线问题数 |
| basic_issues | INT | DEFAULT 0 | 基础问题数 |
| status | VARCHAR(20) | DEFAULT 'normal' | 状态：normal/warning/urgent |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |
| updated_at | DATETIME | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | 更新时间 |

### 3.10 ingredient_acceptances（食材验收表）

存储食材验收质量数据。

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 记录ID |
| school_id | INT | FK, NOT NULL | 学校ID |
| acceptance_date | DATE | NOT NULL | 验收日期 |
| ingredient_id | INT | FK, NOT NULL | 食材ID |
| quantity | DECIMAL(10,2) | DEFAULT 0 | 验收数量 |
| quality_status | TINYINT | DEFAULT 1 | 质量状态：0不合格，1合格，2优良 |
| inspector | VARCHAR(50) | | 验收人 |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |
| updated_at | DATETIME | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | 更新时间 |

### 3.11 evaluations（师生评价表）

存储师生评价数据。

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 记录ID |
| school_id | INT | FK, NOT NULL | 学校ID |
| evaluation_date | DATE | NOT NULL | 评价日期 |
| evaluator_type | VARCHAR(20) | DEFAULT 'student' | 评价者类型：student/teacher |
| score | DECIMAL(4,2) | DEFAULT 0.00 | 评分（0-100） |
| content | VARCHAR(500) | | 评价内容 |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |
| updated_at | DATETIME | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | 更新时间 |

### 3.12 monthly_dispatches（月调度记录表）

存储月度调度情况。

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 记录ID |
| school_id | INT | FK, NOT NULL | 学校ID |
| month | VARCHAR(20) | NOT NULL | 月份（如 2026-01） |
| dispatch_name | VARCHAR(100) | NOT NULL | 调度名称 |
| status | VARCHAR(20) | DEFAULT 'normal' | 状态：normal/warning/urgent/done |
| content | TEXT | | 调度内容 |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |
| updated_at | DATETIME | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | 更新时间 |

### 3.13 price_records（食材价格记录表）

存储食材价格波动数据。

| 字段名 | 类型 | 约束 | 说明 |
|--------|------|------|------|
| id | INT | PK, AUTO_INCREMENT | 记录ID |
| ingredient_id | INT | FK, NOT NULL | 食材ID |
| record_date | DATE | NOT NULL | 记录日期 |
| price | DECIMAL(10,2) | NOT NULL | 价格（元） |
| region | VARCHAR(50) | | 地区 |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |

## 4. 关系说明

| 主表 | 从表 | 关联字段 | 关系类型 |
|------|------|----------|----------|
| schools | purchases | school_id | 1:N |
| schools | orders | school_id | 1:N |
| schools | daily_controls | school_id | 1:N |
| schools | ingredient_acceptances | school_id | 1:N |
| schools | evaluations | school_id | 1:N |
| schools | monthly_dispatches | school_id | 1:N |
| suppliers | purchases | supplier_id | 1:N |
| purchases | purchase_items | purchase_id | 1:N |
| ingredients | purchase_items | ingredient_id | 1:N |
| ingredients | price_records | ingredient_id | 1:N |
| ingredient_categories | ingredients | category_id | 1:N |
| ingredients | ingredient_acceptances | ingredient_id | 1:N |

## 5. 索引设计

除主键索引外，为提升查询性能，在以下字段建立索引：

- `schools.region` — 区域查询
- `purchases.school_id, purchase_date` — 按学校和日期统计
- `orders.school_id, order_date` — 按学校和日期统计
- `daily_controls.school_id, control_date` — 日管控查询
- `price_records.ingredient_id, record_date` — 价格波动查询
- `evaluations.school_id, evaluation_date` — 评价统计
- `ingredient_acceptances.school_id, acceptance_date` — 验收查询

## 6. 大屏数据对应关系

| 大屏板块 | 数据来源表 | 统计方式 |
|----------|-----------|----------|
| 日管控情况汇总 | daily_controls | SUM(total_units, qualified_units, yellow_line_issues, basic_issues) |
| 采购总成本分析 | purchases + purchase_items | 按食材分类 SUM(amount) |
| 膳食经费数据分析 | purchases + orders | 统计待支付/已支付/消费/充值 |
| 订餐数据分析 | orders | 按区域统计 total_count |
| 区域数据分布 | schools + orders | 按 region 统计 |
| 食材单价波动分析 | price_records | 按日期、食材统计 price |
| 学生营养情况分析 | orders | 按 ingredient_category 统计 |
| 供应商评分分析 | suppliers | 直接取 score |
| 月调度情况汇总 | monthly_dispatches | 取最近12条记录 |
| 周排查情况汇总 | daily_controls | SUM 统计 |
| 消费数据分析 | orders | 按月份、区域统计 total_amount |
| 食材验收质量分析 | ingredient_acceptances | 按 quality_status 统计 |
| 师生评价情况分析 | evaluations | 按日期统计 AVG(score) |
