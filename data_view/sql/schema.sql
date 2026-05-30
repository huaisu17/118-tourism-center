-- ========================================================
-- 校园食堂智慧数据管理系统 — 数据库建表脚本
-- 数据库: campus_canteen_db
-- 字符集: utf8mb4
-- 存储引擎: InnoDB
-- ========================================================

CREATE DATABASE IF NOT EXISTS campus_canteen_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE campus_canteen_db;

-- --------------------------------------------------------
-- 1. 用户表 (users)
-- --------------------------------------------------------
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '用户ID',
    username VARCHAR(50) NOT NULL UNIQUE COMMENT '登录用户名',
    password VARCHAR(64) NOT NULL COMMENT 'MD5加密密码',
    real_name VARCHAR(50) NOT NULL COMMENT '真实姓名',
    role TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '角色：0管理员 1普通用户',
    status TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：0禁用 1启用',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户表';

-- --------------------------------------------------------
-- 2. 学校表 (schools)
-- --------------------------------------------------------
CREATE TABLE schools (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '学校ID',
    school_name VARCHAR(100) NOT NULL COMMENT '学校名称',
    region VARCHAR(50) NOT NULL COMMENT '所属区域/县区',
    address VARCHAR(200) DEFAULT NULL COMMENT '详细地址',
    contact_person VARCHAR(50) DEFAULT NULL COMMENT '联系人',
    contact_phone VARCHAR(20) DEFAULT NULL COMMENT '联系电话',
    student_count INT UNSIGNED DEFAULT 0 COMMENT '学生人数',
    teacher_count INT UNSIGNED DEFAULT 0 COMMENT '教师人数',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    INDEX idx_region (region)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='学校表';

-- --------------------------------------------------------
-- 3. 供应商表 (suppliers)
-- --------------------------------------------------------
CREATE TABLE suppliers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '供应商ID',
    supplier_name VARCHAR(100) NOT NULL COMMENT '供应商名称',
    contact_person VARCHAR(50) DEFAULT NULL COMMENT '联系人',
    contact_phone VARCHAR(20) DEFAULT NULL COMMENT '联系电话',
    address VARCHAR(200) DEFAULT NULL COMMENT '地址',
    score DECIMAL(4,2) DEFAULT 0.00 COMMENT '综合评分(0-100)',
    grade VARCHAR(10) DEFAULT NULL COMMENT '评级 A/B/C/D/E',
    status TINYINT UNSIGNED DEFAULT 1 COMMENT '状态：0停用 1合作中',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='供应商表';

-- --------------------------------------------------------
-- 4. 食材分类表 (ingredient_categories)
-- --------------------------------------------------------
CREATE TABLE ingredient_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '分类ID',
    category_name VARCHAR(50) NOT NULL COMMENT '分类名称',
    sort_order INT UNSIGNED DEFAULT 0 COMMENT '排序号',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='食材分类表';

-- --------------------------------------------------------
-- 5. 食材表 (ingredients)
-- --------------------------------------------------------
CREATE TABLE ingredients (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '食材ID',
    ingredient_name VARCHAR(100) NOT NULL COMMENT '食材名称',
    category_id INT UNSIGNED NOT NULL COMMENT '所属分类ID',
    unit VARCHAR(20) DEFAULT 'kg' COMMENT '计量单位',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    CONSTRAINT fk_ingredients_category
        FOREIGN KEY (category_id) REFERENCES ingredient_categories(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='食材表';

-- --------------------------------------------------------
-- 6. 采购记录表 (purchases)
-- --------------------------------------------------------
CREATE TABLE purchases (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '采购ID',
    school_id INT UNSIGNED NOT NULL COMMENT '学校ID',
    supplier_id INT UNSIGNED DEFAULT NULL COMMENT '供应商ID',
    purchase_date DATE NOT NULL COMMENT '采购日期',
    total_amount DECIMAL(12,2) DEFAULT 0.00 COMMENT '采购总金额(元)',
    status TINYINT UNSIGNED DEFAULT 1 COMMENT '状态：0待支付 1已支付',
    remark VARCHAR(500) DEFAULT NULL COMMENT '备注',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    INDEX idx_school_date (school_id, purchase_date),
    CONSTRAINT fk_purchases_school
        FOREIGN KEY (school_id) REFERENCES schools(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_purchases_supplier
        FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='采购记录表';

-- --------------------------------------------------------
-- 7. 采购明细表 (purchase_items)
-- --------------------------------------------------------
CREATE TABLE purchase_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '明细ID',
    purchase_id INT UNSIGNED NOT NULL COMMENT '采购订单ID',
    ingredient_id INT UNSIGNED NOT NULL COMMENT '食材ID',
    quantity DECIMAL(10,2) NOT NULL COMMENT '采购数量',
    unit_price DECIMAL(10,2) NOT NULL COMMENT '单价(元)',
    amount DECIMAL(12,2) NOT NULL COMMENT '金额(元)',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    INDEX idx_purchase (purchase_id),
    CONSTRAINT fk_pitems_purchase
        FOREIGN KEY (purchase_id) REFERENCES purchases(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_pitems_ingredient
        FOREIGN KEY (ingredient_id) REFERENCES ingredients(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='采购明细表';

-- --------------------------------------------------------
-- 8. 订餐记录表 (orders)
-- --------------------------------------------------------
CREATE TABLE orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '记录ID',
    school_id INT UNSIGNED NOT NULL COMMENT '学校ID',
    order_date DATE NOT NULL COMMENT '订餐日期',
    student_count INT UNSIGNED DEFAULT 0 COMMENT '学生订餐数',
    teacher_count INT UNSIGNED DEFAULT 0 COMMENT '教师订餐数',
    total_count INT UNSIGNED DEFAULT 0 COMMENT '总订餐数',
    total_amount DECIMAL(12,2) DEFAULT 0.00 COMMENT '订餐总金额(元)',
    ingredient_category VARCHAR(50) DEFAULT NULL COMMENT '主要营养类别',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    INDEX idx_school_date (school_id, order_date),
    CONSTRAINT fk_orders_school
        FOREIGN KEY (school_id) REFERENCES schools(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='订餐记录表';

-- --------------------------------------------------------
-- 9. 日管控记录表 (daily_controls)
-- --------------------------------------------------------
CREATE TABLE daily_controls (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '记录ID',
    school_id INT UNSIGNED NOT NULL COMMENT '学校ID',
    control_date DATE NOT NULL COMMENT '管控日期',
    total_units INT UNSIGNED DEFAULT 0 COMMENT '排查单位总数',
    qualified_units INT UNSIGNED DEFAULT 0 COMMENT '合格单位数',
    yellow_line_issues INT UNSIGNED DEFAULT 0 COMMENT '黄线问题数',
    basic_issues INT UNSIGNED DEFAULT 0 COMMENT '基础问题数',
    status VARCHAR(20) DEFAULT 'normal' COMMENT '状态 normal/warning/urgent',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    INDEX idx_school_date (school_id, control_date),
    CONSTRAINT fk_controls_school
        FOREIGN KEY (school_id) REFERENCES schools(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='日管控记录表';

-- --------------------------------------------------------
-- 10. 食材验收表 (ingredient_acceptances)
-- --------------------------------------------------------
CREATE TABLE ingredient_acceptances (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '记录ID',
    school_id INT UNSIGNED NOT NULL COMMENT '学校ID',
    acceptance_date DATE NOT NULL COMMENT '验收日期',
    ingredient_id INT UNSIGNED NOT NULL COMMENT '食材ID',
    quantity DECIMAL(10,2) DEFAULT 0 COMMENT '验收数量',
    quality_status TINYINT UNSIGNED DEFAULT 1 COMMENT '质量状态：0不合格 1合格 2优良',
    inspector VARCHAR(50) DEFAULT NULL COMMENT '验收人',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    INDEX idx_school_date (school_id, acceptance_date),
    CONSTRAINT fk_acceptances_school
        FOREIGN KEY (school_id) REFERENCES schools(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_acceptances_ingredient
        FOREIGN KEY (ingredient_id) REFERENCES ingredients(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='食材验收表';

-- --------------------------------------------------------
-- 11. 师生评价表 (evaluations)
-- --------------------------------------------------------
CREATE TABLE evaluations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '记录ID',
    school_id INT UNSIGNED NOT NULL COMMENT '学校ID',
    evaluation_date DATE NOT NULL COMMENT '评价日期',
    evaluator_type VARCHAR(20) DEFAULT 'student' COMMENT '评价者类型 student/teacher',
    score DECIMAL(4,2) DEFAULT 0.00 COMMENT '评分(0-100)',
    content VARCHAR(500) DEFAULT NULL COMMENT '评价内容',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    INDEX idx_school_date (school_id, evaluation_date),
    CONSTRAINT fk_evaluations_school
        FOREIGN KEY (school_id) REFERENCES schools(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='师生评价表';

-- --------------------------------------------------------
-- 12. 月调度记录表 (monthly_dispatches)
-- --------------------------------------------------------
CREATE TABLE monthly_dispatches (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '记录ID',
    school_id INT UNSIGNED NOT NULL COMMENT '学校ID',
    month VARCHAR(20) NOT NULL COMMENT '月份 如 2026-01',
    dispatch_name VARCHAR(100) NOT NULL COMMENT '调度名称',
    status VARCHAR(20) DEFAULT 'normal' COMMENT '状态 normal/warning/urgent/done',
    content TEXT DEFAULT NULL COMMENT '调度内容',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    INDEX idx_school_month (school_id, month),
    CONSTRAINT fk_dispatches_school
        FOREIGN KEY (school_id) REFERENCES schools(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='月调度记录表';

-- --------------------------------------------------------
-- 13. 食材价格记录表 (price_records)
-- --------------------------------------------------------
CREATE TABLE price_records (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '记录ID',
    ingredient_id INT UNSIGNED NOT NULL COMMENT '食材ID',
    record_date DATE NOT NULL COMMENT '记录日期',
    price DECIMAL(10,2) NOT NULL COMMENT '价格(元)',
    region VARCHAR(50) DEFAULT NULL COMMENT '地区',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    INDEX idx_ingredient_date (ingredient_id, record_date),
    CONSTRAINT fk_prices_ingredient
        FOREIGN KEY (ingredient_id) REFERENCES ingredients(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='食材价格记录表';
