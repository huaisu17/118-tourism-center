-- ============================================================
-- 智校数据综合展示中心 — 数据库建表脚本
-- 数据库: MySQL 8.0
-- 字符集: utf8mb4
-- 存储引擎: InnoDB
-- 生成日期: 2026-06-04
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- 1. 区域表
-- ============================================================
DROP TABLE IF EXISTS `region`;
CREATE TABLE `region` (
    `region_id`       INT PRIMARY KEY AUTO_INCREMENT COMMENT '区域ID',
    `region_name`     VARCHAR(50)  NOT NULL COMMENT '区域名称',
    `region_code`     VARCHAR(20)  NOT NULL UNIQUE COMMENT '区域编码',
    `parent_id`       INT          COMMENT '父区域ID（树形结构）'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='行政区域表';

-- ============================================================
-- 2. 学校表
-- ============================================================
DROP TABLE IF EXISTS `school`;
CREATE TABLE `school` (
    `school_id`       INT PRIMARY KEY AUTO_INCREMENT COMMENT '学校ID',
    `region_id`       INT          NOT NULL COMMENT '所属区域ID',
    `school_name`     VARCHAR(100) NOT NULL COMMENT '学校名称',
    `school_code`     VARCHAR(20)  NOT NULL UNIQUE COMMENT '学校编码',
    `school_type`     VARCHAR(20)  COMMENT '学校类型：小学/初中/高中/大学',
    `address`         VARCHAR(200) COMMENT '详细地址',
    `student_count`   INT DEFAULT 0 COMMENT '学生人数',
    `teacher_count`   INT DEFAULT 0 COMMENT '教师人数',
    `contact_person`  VARCHAR(50)  COMMENT '联系人',
    `contact_phone`   VARCHAR(20)  COMMENT '联系电话',
    `status`          TINYINT DEFAULT 1 COMMENT '状态：0停用，1启用',
    `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at`      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    FOREIGN KEY (`region_id`) REFERENCES `region`(`region_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='学校表';

-- ============================================================
-- 3. 食堂表
-- ============================================================
DROP TABLE IF EXISTS `canteen`;
CREATE TABLE `canteen` (
    `canteen_id`      INT PRIMARY KEY AUTO_INCREMENT COMMENT '食堂ID',
    `school_id`       INT          NOT NULL COMMENT '所属学校ID',
    `canteen_name`    VARCHAR(100) NOT NULL COMMENT '食堂名称',
    `canteen_code`    VARCHAR(20)  NOT NULL UNIQUE COMMENT '食堂编码',
    `canteen_type`    VARCHAR(20)  COMMENT '类型：自营/外包',
    `manager_name`    VARCHAR(50)  COMMENT '负责人姓名',
    `manager_phone`   VARCHAR(20)  COMMENT '负责人电话',
    `capacity`        INT DEFAULT 0 COMMENT '容纳人数',
    `status`          TINYINT DEFAULT 1 COMMENT '状态：0停用，1运营中',
    `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at`      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    FOREIGN KEY (`school_id`) REFERENCES `school`(`school_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='食堂表';

-- ============================================================
-- 4. 供应商表
-- ============================================================
DROP TABLE IF EXISTS `supplier`;
CREATE TABLE `supplier` (
    `supplier_id`     INT PRIMARY KEY AUTO_INCREMENT COMMENT '供应商ID',
    `supplier_name`   VARCHAR(100) NOT NULL COMMENT '供应商名称',
    `supplier_code`   VARCHAR(20)  NOT NULL UNIQUE COMMENT '供应商编码',
    `contact_person`  VARCHAR(50)  COMMENT '联系人',
    `contact_phone`   VARCHAR(20)  COMMENT '联系电话',
    `address`         VARCHAR(200) COMMENT '地址',
    `license_no`      VARCHAR(50)  COMMENT '营业执照号',
    `score`           DECIMAL(4,2) DEFAULT 0.00 COMMENT '综合评分（0-100）',
    `grade`           VARCHAR(10)  COMMENT '评级：A/B/C/D',
    `status`          TINYINT DEFAULT 1 COMMENT '状态：0停用，1合作中',
    `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at`      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='供应商表';

-- ============================================================
-- 5. 食材分类表
-- ============================================================
DROP TABLE IF EXISTS `ingredient_category`;
CREATE TABLE `ingredient_category` (
    `category_id`     INT PRIMARY KEY AUTO_INCREMENT COMMENT '分类ID',
    `category_name`   VARCHAR(50)  NOT NULL COMMENT '分类名称',
    `category_code`   VARCHAR(20)  NOT NULL UNIQUE COMMENT '分类编码',
    `parent_id`       INT          COMMENT '父分类ID（树形结构）',
    `sort_order`      INT DEFAULT 0 COMMENT '排序号',
    `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    FOREIGN KEY (`parent_id`) REFERENCES `ingredient_category`(`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='食材分类表';

-- ============================================================
-- 6. 食材表
-- ============================================================
DROP TABLE IF EXISTS `ingredient`;
CREATE TABLE `ingredient` (
    `ingredient_id`   INT PRIMARY KEY AUTO_INCREMENT COMMENT '食材ID',
    `category_id`     INT          NOT NULL COMMENT '所属分类ID',
    `ingredient_name` VARCHAR(100) NOT NULL COMMENT '食材名称',
    `ingredient_code` VARCHAR(20)  NOT NULL UNIQUE COMMENT '食材编码',
    `specification`   VARCHAR(100) COMMENT '规格',
    `unit`            VARCHAR(20)  DEFAULT 'kg' COMMENT '计量单位',
    `storage_condition` VARCHAR(50) COMMENT '储存条件',
    `status`          TINYINT DEFAULT 1 COMMENT '状态：0停用，1启用',
    `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    FOREIGN KEY (`category_id`) REFERENCES `ingredient_category`(`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='食材表';

-- ============================================================
-- 7. 采购订单表
-- ============================================================
DROP TABLE IF EXISTS `purchase_order`;
CREATE TABLE `purchase_order` (
    `purchase_id`     INT PRIMARY KEY AUTO_INCREMENT COMMENT '采购ID',
    `school_id`       INT          NOT NULL COMMENT '学校ID',
    `canteen_id`      INT          NOT NULL COMMENT '食堂ID',
    `supplier_id`     INT          COMMENT '供应商ID',
    `order_no`        VARCHAR(50)  NOT NULL UNIQUE COMMENT '订单编号',
    `purchase_date`   DATE         NOT NULL COMMENT '采购日期',
    `total_amount`    DECIMAL(12,2) DEFAULT 0.00 COMMENT '采购总金额（元）',
    `paid_amount`     DECIMAL(12,2) DEFAULT 0.00 COMMENT '已支付金额（元）',
    `unpaid_amount`   DECIMAL(12,2) DEFAULT 0.00 COMMENT '待支付金额（元）',
    `pay_status`      VARCHAR(20)  DEFAULT '待支付' COMMENT '支付状态：待支付/已支付/部分支付',
    `payment_date`    DATE         COMMENT '支付日期',
    `remark`          VARCHAR(500) COMMENT '备注',
    `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at`      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    FOREIGN KEY (`school_id`) REFERENCES `school`(`school_id`),
    FOREIGN KEY (`canteen_id`) REFERENCES `canteen`(`canteen_id`),
    FOREIGN KEY (`supplier_id`) REFERENCES `supplier`(`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='采购订单表';

-- ============================================================
-- 8. 采购明细表（n:m 关联表）
-- ============================================================
DROP TABLE IF EXISTS `purchase_item`;
CREATE TABLE `purchase_item` (
    `item_id`         INT PRIMARY KEY AUTO_INCREMENT COMMENT '明细ID',
    `purchase_id`     INT          NOT NULL COMMENT '采购订单ID',
    `ingredient_id`   INT          NOT NULL COMMENT '食材ID',
    `quantity`        DECIMAL(10,2) NOT NULL COMMENT '采购数量',
    `unit_price`      DECIMAL(10,2) NOT NULL COMMENT '单价（元）',
    `amount`          DECIMAL(12,2) NOT NULL COMMENT '金额（元）',
    `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    FOREIGN KEY (`purchase_id`) REFERENCES `purchase_order`(`purchase_id`),
    FOREIGN KEY (`ingredient_id`) REFERENCES `ingredient`(`ingredient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='采购明细表';

-- ============================================================
-- 9. 食材价格记录表
-- ============================================================
DROP TABLE IF EXISTS `ingredient_price`;
CREATE TABLE `ingredient_price` (
    `price_id`        INT PRIMARY KEY AUTO_INCREMENT COMMENT '记录ID',
    `ingredient_id`   INT          NOT NULL COMMENT '食材ID',
    `record_date`     DATE         NOT NULL COMMENT '记录日期',
    `price`           DECIMAL(10,2) NOT NULL COMMENT '价格（元）',
    `region`          VARCHAR(50)  COMMENT '地区',
    `source`          VARCHAR(100) COMMENT '数据来源',
    `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    FOREIGN KEY (`ingredient_id`) REFERENCES `ingredient`(`ingredient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='食材价格记录表';

-- ============================================================
-- 10. 日管控记录表
-- ============================================================
DROP TABLE IF EXISTS `daily_control`;
CREATE TABLE `daily_control` (
    `control_id`      INT PRIMARY KEY AUTO_INCREMENT COMMENT '记录ID',
    `school_id`       INT          NOT NULL COMMENT '学校ID',
    `canteen_id`      INT          NOT NULL COMMENT '食堂ID',
    `control_date`    DATE         NOT NULL COMMENT '管控日期',
    `total_count`     INT DEFAULT 0 COMMENT '排查项目总数',
    `completed_count` INT DEFAULT 0 COMMENT '已完成数',
    `pending_rectification_count` INT DEFAULT 0 COMMENT '待整改数',
    `status`          VARCHAR(20)  DEFAULT 'normal' COMMENT '状态：normal/warning/urgent',
    `inspector`       VARCHAR(50)  COMMENT '检查人',
    `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at`      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    FOREIGN KEY (`school_id`) REFERENCES `school`(`school_id`),
    FOREIGN KEY (`canteen_id`) REFERENCES `canteen`(`canteen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='日管控记录表';

-- ============================================================
-- 11. 管控明细表
-- ============================================================
DROP TABLE IF EXISTS `daily_control_item`;
CREATE TABLE `daily_control_item` (
    `item_id`         INT PRIMARY KEY AUTO_INCREMENT COMMENT '明细ID',
    `control_id`      INT          NOT NULL COMMENT '管控记录ID',
    `item_name`       VARCHAR(100) NOT NULL COMMENT '检查项名称',
    `check_result`    VARCHAR(20)  COMMENT '检查结果：合格/不合格',
    `issue_description` VARCHAR(200) COMMENT '问题描述',
    `rectification_status` VARCHAR(20) COMMENT '整改状态：待整改/已整改',
    `rectification_time` DATETIME COMMENT '整改时间',
    `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    FOREIGN KEY (`control_id`) REFERENCES `daily_control`(`control_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='日管控明细表';

-- ============================================================
-- 12. 周排查记录表
-- ============================================================
DROP TABLE IF EXISTS `weekly_inspection`;
CREATE TABLE `weekly_inspection` (
    `inspection_id`   INT PRIMARY KEY AUTO_INCREMENT COMMENT '记录ID',
    `school_id`       INT          NOT NULL COMMENT '学校ID',
    `inspection_week` VARCHAR(20)  NOT NULL COMMENT '排查周次（如 2026-W01）',
    `total_units`     INT DEFAULT 0 COMMENT '排查单位总数',
    `qualified_units` INT DEFAULT 0 COMMENT '合格单位数',
    `yellow_line_issues` INT DEFAULT 0 COMMENT '黄线问题数',
    `basic_issues`    INT DEFAULT 0 COMMENT '基础问题数',
    `qualified_rate`  DECIMAL(5,2) DEFAULT 0.00 COMMENT '合格率',
    `status`          VARCHAR(20)  DEFAULT 'normal' COMMENT '状态：normal/warning/urgent',
    `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at`      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    FOREIGN KEY (`school_id`) REFERENCES `school`(`school_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='周排查记录表';

-- ============================================================
-- 13. 月调度记录表
-- ============================================================
DROP TABLE IF EXISTS `monthly_dispatch`;
CREATE TABLE `monthly_dispatch` (
    `dispatch_id`     INT PRIMARY KEY AUTO_INCREMENT COMMENT '记录ID',
    `school_id`       INT          NOT NULL COMMENT '学校ID',
    `canteen_id`      INT          COMMENT '食堂ID（可选）',
    `month`           VARCHAR(20)  NOT NULL COMMENT '月份（如 2026-01）',
    `dispatch_name`   VARCHAR(100) NOT NULL COMMENT '调度名称',
    `status`          VARCHAR(20)  DEFAULT 'normal' COMMENT '状态：normal/warning/urgent/done',
    `content`         TEXT         COMMENT '调度内容',
    `detail_url`      VARCHAR(200) COMMENT '详情页URL',
    `page_code`       VARCHAR(50)  COMMENT '页面编码',
    `dispatch_time`   DATETIME     COMMENT '调度时间',
    `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at`      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    FOREIGN KEY (`school_id`) REFERENCES `school`(`school_id`),
    FOREIGN KEY (`canteen_id`) REFERENCES `canteen`(`canteen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='月调度记录表';

-- ============================================================
-- 14. 订餐记录表
-- ============================================================
DROP TABLE IF EXISTS `order_record`;
CREATE TABLE `order_record` (
    `order_id`        INT PRIMARY KEY AUTO_INCREMENT COMMENT '记录ID',
    `school_id`       INT          NOT NULL COMMENT '学校ID',
    `canteen_id`      INT          NOT NULL COMMENT '食堂ID',
    `order_date`      DATE         NOT NULL COMMENT '订餐日期',
    `meal_type`       VARCHAR(20)  COMMENT '餐次：早餐/午餐/晚餐',
    `dish_name`       VARCHAR(100) COMMENT '菜品名称',
    `student_count`   INT DEFAULT 0 COMMENT '学生订餐数',
    `teacher_count`   INT DEFAULT 0 COMMENT '教师订餐数',
    `total_count`     INT DEFAULT 0 COMMENT '总订餐数',
    `total_amount`    DECIMAL(12,2) DEFAULT 0.00 COMMENT '订餐总金额（元）',
    `ingredient_category` VARCHAR(50) COMMENT '主要营养类别',
    `change_rate`     DECIMAL(5,2) COMMENT '环比变化率',
    `rank_num`        INT COMMENT '排名',
    `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at`      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    FOREIGN KEY (`school_id`) REFERENCES `school`(`school_id`),
    FOREIGN KEY (`canteen_id`) REFERENCES `canteen`(`canteen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订餐记录表';

-- ============================================================
-- 15. 食材验收表
-- ============================================================
DROP TABLE IF EXISTS `ingredient_acceptance`;
CREATE TABLE `ingredient_acceptance` (
    `acceptance_id`   INT PRIMARY KEY AUTO_INCREMENT COMMENT '记录ID',
    `school_id`       INT          NOT NULL COMMENT '学校ID',
    `canteen_id`      INT          NOT NULL COMMENT '食堂ID',
    `ingredient_id`   INT          NOT NULL COMMENT '食材ID',
    `acceptance_date` DATE         NOT NULL COMMENT '验收日期',
    `batch_no`        VARCHAR(50)  COMMENT '批次号',
    `quantity`        DECIMAL(10,2) DEFAULT 0 COMMENT '验收数量',
    `quality_status`  TINYINT DEFAULT 1 COMMENT '质量状态：0不合格/1合格/2优良',
    `acceptance_rate` DECIMAL(5,2) DEFAULT 0.00 COMMENT '验收合格率',
    `inspector`       VARCHAR(50)  COMMENT '验收人',
    `remark`          VARCHAR(500) COMMENT '备注',
    `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at`      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    FOREIGN KEY (`school_id`) REFERENCES `school`(`school_id`),
    FOREIGN KEY (`canteen_id`) REFERENCES `canteen`(`canteen_id`),
    FOREIGN KEY (`ingredient_id`) REFERENCES `ingredient`(`ingredient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='食材验收表';

-- ============================================================
-- 16. 师生评价表
-- ============================================================
DROP TABLE IF EXISTS `evaluation`;
CREATE TABLE `evaluation` (
    `evaluation_id`   INT PRIMARY KEY AUTO_INCREMENT COMMENT '记录ID',
    `school_id`       INT          NOT NULL COMMENT '学校ID',
    `canteen_id`      INT          NOT NULL COMMENT '食堂ID',
    `evaluation_date` DATE         NOT NULL COMMENT '评价日期',
    `evaluator_type`  VARCHAR(20)  DEFAULT 'student' COMMENT '评价者类型：student/teacher',
    `evaluator_name`  VARCHAR(50)  COMMENT '评价者姓名',
    `score`           DECIMAL(4,2) DEFAULT 0.00 COMMENT '评分（0-100）',
    `content`         VARCHAR(500) COMMENT '评价内容',
    `dish_name`       VARCHAR(100) COMMENT '评价菜品',
    `is_anonymous`    TINYINT DEFAULT 0 COMMENT '是否匿名：0否，1是',
    `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at`      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    FOREIGN KEY (`school_id`) REFERENCES `school`(`school_id`),
    FOREIGN KEY (`canteen_id`) REFERENCES `canteen`(`canteen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='师生评价表';

-- ============================================================
-- 17. 学生营养记录表
-- ============================================================
DROP TABLE IF EXISTS `student_nutrition`;
CREATE TABLE `student_nutrition` (
    `nutrition_id`    INT PRIMARY KEY AUTO_INCREMENT COMMENT '记录ID',
    `school_id`       INT          NOT NULL COMMENT '学校ID',
    `canteen_id`      INT          NOT NULL COMMENT '食堂ID',
    `record_date`     DATE         NOT NULL COMMENT '记录日期',
    `ingredient_id`   INT          COMMENT '食材ID',
    `category_name`   VARCHAR(50)  COMMENT '营养类别：副食/生鲜/粮油',
    `intake_amount`   DECIMAL(10,2) DEFAULT 0 COMMENT '摄入量',
    `protein`         DECIMAL(8,2) DEFAULT 0 COMMENT '蛋白质（g）',
    `fat`             DECIMAL(8,2) DEFAULT 0 COMMENT '脂肪（g）',
    `carbohydrate`    DECIMAL(8,2) DEFAULT 0 COMMENT '碳水化合物（g）',
    `calories`        DECIMAL(8,2) DEFAULT 0 COMMENT '热量（kcal）',
    `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    FOREIGN KEY (`school_id`) REFERENCES `school`(`school_id`),
    FOREIGN KEY (`canteen_id`) REFERENCES `canteen`(`canteen_id`),
    FOREIGN KEY (`ingredient_id`) REFERENCES `ingredient`(`ingredient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='学生营养记录表';

-- ============================================================
-- 18. 消费记录表
-- ============================================================
DROP TABLE IF EXISTS `consumption_record`;
CREATE TABLE `consumption_record` (
    `consumption_id`  INT PRIMARY KEY AUTO_INCREMENT COMMENT '记录ID',
    `school_id`       INT          NOT NULL COMMENT '学校ID',
    `canteen_id`      INT          NOT NULL COMMENT '食堂ID',
    `month`           VARCHAR(20)  NOT NULL COMMENT '月份（如 2026-01）',
    `consumption_amount` DECIMAL(12,2) DEFAULT 0.00 COMMENT '消费金额（元）',
    `student_count`   INT DEFAULT 0 COMMENT '消费人次',
    `avg_amount`      DECIMAL(10,2) DEFAULT 0.00 COMMENT '人均消费（元）',
    `region_name`     VARCHAR(50)  COMMENT '区域名称',
    `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    FOREIGN KEY (`school_id`) REFERENCES `school`(`school_id`),
    FOREIGN KEY (`canteen_id`) REFERENCES `canteen`(`canteen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='消费记录表';

-- ============================================================
-- 19. 供应商食堂食材关联表（三元关联表）
-- ============================================================
DROP TABLE IF EXISTS `supplier_canteen_ingredient`;
CREATE TABLE `supplier_canteen_ingredient` (
    `link_id`         INT PRIMARY KEY AUTO_INCREMENT COMMENT '关联ID',
    `supplier_id`     INT          NOT NULL COMMENT '供应商ID',
    `canteen_id`      INT          NOT NULL COMMENT '食堂ID',
    `ingredient_id`   INT          NOT NULL COMMENT '食材ID',
    `supply_price`    DECIMAL(10,2) DEFAULT 0.00 COMMENT '供应单价（元）',
    `is_primary`      TINYINT DEFAULT 0 COMMENT '是否主供应商：0否，1是',
    `created_at`      DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    FOREIGN KEY (`supplier_id`) REFERENCES `supplier`(`supplier_id`),
    FOREIGN KEY (`canteen_id`) REFERENCES `canteen`(`canteen_id`),
    FOREIGN KEY (`ingredient_id`) REFERENCES `ingredient`(`ingredient_id`),
    UNIQUE KEY `uk_supplier_canteen_ingredient` (`supplier_id`, `canteen_id`, `ingredient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='供应商食堂食材关联表';

SET FOREIGN_KEY_CHECKS = 1;
