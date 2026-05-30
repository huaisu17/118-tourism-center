# 校园食堂智慧数据管理系统

数据库课程大作业 —— 基于 PHP + MySQL 的 Web 应用系统

## 项目概述

本系统为校园食堂及后勤数据管理平台，包含：
- **管理后台**：12个功能模块的完整增删改查（CRUD）
- **数据大屏**：4K分辨率实时数据可视化展示
- **统一API**：为数据大屏提供动态数据接口

## 技术栈

- **后端**: PHP 8.0 + PDO + MySQL 8.0
- **前端**: HTML5 + CSS3 + JavaScript + jQuery + ECharts 4.x
- **UI框架**: Bootstrap 5 (管理后台)
- **开发环境**: phpStudy (Apache + PHP + MySQL)

## 项目结构

```
├── admin/              # 管理后台
│   ├── login.php       # 登录页
│   ├── index.php       # 仪表盘
│   ├── header.php      # 公共头部
│   ├── footer.php      # 公共底部
│   ├── schools.php     # 学校管理
│   ├── suppliers.php   # 供应商管理
│   ├── ingredients.php # 食材管理
│   ├── purchases.php   # 采购管理
│   ├── orders.php      # 订餐管理
│   ├── daily_controls.php   # 日管控管理
│   ├── acceptances.php # 验收管理
│   ├── evaluations.php # 评价管理
│   ├── dispatches.php  # 调度管理
│   ├── prices.php      # 价格管理
│   └── users.php       # 用户管理(管理员)
├── api/
│   └── data.php        # 大屏数据统一接口
├── dashboard/
│   ├── index.php       # 数据大屏页面
│   └── data.php        # 大屏动态数据桥接
├── includes/
│   ├── config.php      # 数据库配置
│   ├── db.php          # 数据库连接封装
│   ├── functions.php   # 公共函数
│   └── auth.php        # 认证与权限
├── sql/
│   ├── schema.sql      # 数据库建表脚本
│   └── seed_data.sql   # 初始模拟数据
├── docs/
│   ├── requirements.md # 需求分析文档
│   └── database_design.md  # 数据库设计文档
├── tests/
│   └── test_cases.md   # 测试用例文档
├── cs/                 # 大屏样式
├── js/                 # 大屏脚本
├── images/             # 图片资源
└── index.html          # 原始静态大屏(保留)
```

## 快速部署

### 1. 环境准备

安装 [phpStudy](https://www.xp.cn/)，启动 Apache 和 MySQL 服务。

### 2. 导入数据库

```bash
# 使用 phpMyAdmin 或命令行
mysql -u root -p < sql/schema.sql
mysql -u root -p < sql/seed_data.sql
```

### 3. 配置数据库

编辑 `includes/config.php`，根据实际环境修改数据库连接信息：

```php
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'campus_canteen_db');
define('DB_USER', 'root');
define('DB_PASS', 'root');
```

### 4. 访问系统

- **管理后台**: http://localhost/admin/login.php
- **数据大屏**: http://localhost/dashboard/index.php
- **API接口**: http://localhost/api/data.php?type=daily_control

### 5. 默认账号

| 用户名 | 密码 | 角色 |
|--------|------|------|
| admin | admin123 | 管理员 |
| zhangsan | 123456 | 普通用户 |
| lisi | 123456 | 普通用户 |
| wangwu | 123456 | 普通用户 |

## 功能模块

| 模块 | 功能描述 |
|------|----------|
| 学校管理 | 学校信息的增删改查，支持按区域搜索 |
| 供应商管理 | 供应商信息维护，评分与评级管理 |
| 食材管理 | 食材分类与食材信息管理 |
| 采购管理 | 采购订单录入，支持多明细自动计算金额 |
| 订餐管理 | 每日订餐数据统计，学生/教师分类 |
| 日管控管理 | 每日排查数据记录，状态预警 |
| 验收管理 | 食材验收质量跟踪 |
| 评价管理 | 师生评价收集与评分统计 |
| 调度管理 | 月度调度情况汇总 |
| 价格管理 | 食材价格波动记录 |
| 用户管理 | 系统用户与权限管理 |
| 数据大屏 | 12个数据板块的实时可视化展示 |

## 数据库设计

共13张表：
- `users` - 用户表
- `schools` - 学校表
- `suppliers` - 供应商表
- `ingredient_categories` - 食材分类表
- `ingredients` - 食材表
- `purchases` - 采购记录表
- `purchase_items` - 采购明细表
- `orders` - 订餐记录表
- `daily_controls` - 日管控记录表
- `ingredient_acceptances` - 食材验收表
- `evaluations` - 师生评价表
- `monthly_dispatches` - 月调度记录表
- `price_records` - 食材价格记录表

详见 `docs/database_design.md`

## API接口

统一接口地址：`api/data.php?type={type}`

| type | 说明 |
|------|------|
| daily_control | 日管控情况汇总 |
| purchase_cost | 采购总成本分析 |
| meal_fund | 膳食经费数据分析 |
| order_analysis | 订餐数据分析 |
| region_distribution | 区域数据分布 |
| price_trend | 食材单价波动分析 |
| nutrition | 学生营养情况分析 |
| supplier_score | 供应商评分分析 |
| monthly_dispatch | 月调度情况汇总 |
| weekly_inspection | 周排查情况汇总 |
| consumption | 消费数据分析 |
| acceptance_quality | 食材验收质量分析 |
| evaluation | 师生评价情况分析 |

## 测试

测试用例文档：`tests/test_cases.md`

包含30个测试用例，覆盖：
- 数据库完整性测试
- 用户认证与权限测试
- 各模块CRUD功能测试
- 数据大屏展示测试
- API接口测试
- 性能与兼容性测试
