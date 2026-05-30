# 智校数据综合展示中心 — 数据库 ER 图（Chen's Notation）

> 本文档使用 **Graphviz DOT** 语法编写经典 Chen's Notation（陈式ER图），可直接用 image2 插件或 Graphviz 命令行渲染生成图片。

---

## 完整 ER 图（DOT 代码）

将下方代码块保存为 `.dot` 文件后，使用 image2 预览，或运行 `dot -Tpng er.dot -o er.png` 生成图片。

```dot
digraph ER {
    graph [fontname="Microsoft YaHei", fontsize=12, rankdir=TB,
           bgcolor=white, margin=0, nodesep=0.4, ranksep=1.0];
    node [fontname="Microsoft YaHei", fontsize=9,
          shape=rectangle, style=filled, fillcolor="#f8f9fa",
          width=0, height=0, margin="0.12,0.06"];
    edge [fontname="Microsoft YaHei", fontsize=9,
          arrowhead=none, color="#333333"];

    /* ============================================================
       第一层：区域
       ============================================================ */
    Region [label="区域"];
    reg_id [shape=ellipse, label=<<U>区域ID</U>>, fillcolor=white];
    reg_name [shape=ellipse, label="区域名称", fillcolor=white];
    reg_code [shape=ellipse, label="区域编码", fillcolor=white];
    Region -> reg_id [len=0.01];
    Region -> reg_name [len=0.01];
    Region -> reg_code [len=0.01];

    /* ============================================================
       第二层：学校、供应商、食材分类
       ============================================================ */
    { rank=same;
      School [label="学校"];
      Supplier [label="供应商"];
      IngCat [label="食材分类"];
    }

    /* 学校属性 */
    sch_id [shape=ellipse, label=<<U>学校ID</U>>, fillcolor=white];
    sch_regid [shape=ellipse, label="区域ID", fillcolor=white];
    sch_name [shape=ellipse, label="学校名称", fillcolor=white];
    sch_code [shape=ellipse, label="学校编码", fillcolor=white];
    sch_type [shape=ellipse, label="学校类型", fillcolor=white];
    sch_addr [shape=ellipse, label="地址", fillcolor=white];
    sch_stu [shape=ellipse, label="学生人数", fillcolor=white];
    sch_tea [shape=ellipse, label="教师人数", fillcolor=white];
    School -> sch_id [len=0.01];
    School -> sch_regid [len=0.01];
    School -> sch_name [len=0.01];
    School -> sch_code [len=0.01];
    School -> sch_type [len=0.01];
    School -> sch_addr [len=0.01];
    School -> sch_stu [len=0.01];
    School -> sch_tea [len=0.01];

    /* 供应商属性 */
    sup_id [shape=ellipse, label=<<U>供应商ID</U>>, fillcolor=white];
    sup_name [shape=ellipse, label="供应商名称", fillcolor=white];
    sup_code [shape=ellipse, label="供应商编码", fillcolor=white];
    sup_person [shape=ellipse, label="联系人", fillcolor=white];
    sup_phone [shape=ellipse, label="联系电话", fillcolor=white];
    sup_score [shape=ellipse, label="综合评分", fillcolor=white];
    sup_grade [shape=ellipse, label="评级", fillcolor=white];
    sup_status [shape=ellipse, label="状态", fillcolor=white];
    Supplier -> sup_id [len=0.01];
    Supplier -> sup_name [len=0.01];
    Supplier -> sup_code [len=0.01];
    Supplier -> sup_person [len=0.01];
    Supplier -> sup_phone [len=0.01];
    Supplier -> sup_score [len=0.01];
    Supplier -> sup_grade [len=0.01];
    Supplier -> sup_status [len=0.01];

    /* 食材分类属性 */
    cat_id [shape=ellipse, label=<<U>分类ID</U>>, fillcolor=white];
    cat_name [shape=ellipse, label="分类名称", fillcolor=white];
    cat_code [shape=ellipse, label="分类编码", fillcolor=white];
    cat_pid [shape=ellipse, label="父分类ID", fillcolor=white];
    IngCat -> cat_id [len=0.01];
    IngCat -> cat_name [len=0.01];
    IngCat -> cat_code [len=0.01];
    IngCat -> cat_pid [len=0.01];

    /* ============================================================
       关系：区域 ——包含——> 学校
       ============================================================ */
    R1 [shape=diamond, label="包含", fillcolor="#e3f2fd"];
    Region -> R1 [label="1", len=0.3];
    R1 -> School [label="N", len=0.3];

    /* ============================================================
       第三层：食堂、食材
       ============================================================ */
    { rank=same;
      Canteen [label="食堂"];
      Ingredient [label="食材"];
    }

    /* 食堂属性 */
    can_id [shape=ellipse, label=<<U>食堂ID</U>>, fillcolor=white];
    can_schid [shape=ellipse, label="学校ID", fillcolor=white];
    can_name [shape=ellipse, label="食堂名称", fillcolor=white];
    can_code [shape=ellipse, label="食堂编码", fillcolor=white];
    can_type [shape=ellipse, label="食堂类型", fillcolor=white];
    can_mgr [shape=ellipse, label="负责人", fillcolor=white];
    can_cap [shape=ellipse, label="容纳人数", fillcolor=white];
    Canteen -> can_id [len=0.01];
    Canteen -> can_schid [len=0.01];
    Canteen -> can_name [len=0.01];
    Canteen -> can_code [len=0.01];
    Canteen -> can_type [len=0.01];
    Canteen -> can_mgr [len=0.01];
    Canteen -> can_cap [len=0.01];

    /* 食材属性 */
    ing_id [shape=ellipse, label=<<U>食材ID</U>>, fillcolor=white];
    ing_catid [shape=ellipse, label="分类ID", fillcolor=white];
    ing_name [shape=ellipse, label="食材名称", fillcolor=white];
    ing_code [shape=ellipse, label="食材编码", fillcolor=white];
    ing_spec [shape=ellipse, label="规格", fillcolor=white];
    ing_unit [shape=ellipse, label="计量单位", fillcolor=white];
    Ingredient -> ing_id [len=0.01];
    Ingredient -> ing_catid [len=0.01];
    Ingredient -> ing_name [len=0.01];
    Ingredient -> ing_code [len=0.01];
    Ingredient -> ing_spec [len=0.01];
    Ingredient -> ing_unit [len=0.01];

    /* ============================================================
       关系：学校 ——拥有——> 食堂
              ——产生——> 各业务记录
              食材分类 ——分类——> 食材
       ============================================================ */
    R2 [shape=diamond, label="拥有", fillcolor="#e3f2fd"];
    School -> R2 [label="1", len=0.3];
    R2 -> Canteen [label="N", len=0.3];

    R3 [shape=diamond, label="分类", fillcolor="#e3f2fd"];
    IngCat -> R3 [label="1", len=0.3];
    R3 -> Ingredient [label="N", len=0.3];

    /* ============================================================
       第四层：采购订单、食材价格、供应商食材关联
       ============================================================ */
    { rank=same;
      Purchase [label="采购订单"];
      PriceRec [label="食材价格"];
      SupIng [label="供应商食材关联"];
    }

    /* 采购订单属性 */
    pur_id [shape=ellipse, label=<<U>采购ID</U>>, fillcolor=white];
    pur_schid [shape=ellipse, label="学校ID", fillcolor=white];
    pur_canid [shape=ellipse, label="食堂ID", fillcolor=white];
    pur_supid [shape=ellipse, label="供应商ID", fillcolor=white];
    pur_no [shape=ellipse, label="订单编号", fillcolor=white];
    pur_date [shape=ellipse, label="采购日期", fillcolor=white];
    pur_total [shape=ellipse, label="总金额", fillcolor=white];
    pur_paid [shape=ellipse, label="已支付", fillcolor=white];
    pur_unpaid [shape=ellipse, label="待支付", fillcolor=white];
    pur_status [shape=ellipse, label="支付状态", fillcolor=white];
    Purchase -> pur_id [len=0.01];
    Purchase -> pur_schid [len=0.01];
    Purchase -> pur_canid [len=0.01];
    Purchase -> pur_supid [len=0.01];
    Purchase -> pur_no [len=0.01];
    Purchase -> pur_date [len=0.01];
    Purchase -> pur_total [len=0.01];
    Purchase -> pur_paid [len=0.01];
    Purchase -> pur_unpaid [len=0.01];
    Purchase -> pur_status [len=0.01];

    /* 食材价格属性 */
    pr_id [shape=ellipse, label=<<U>记录ID</U>>, fillcolor=white];
    pr_ingid [shape=ellipse, label="食材ID", fillcolor=white];
    pr_date [shape=ellipse, label="记录日期", fillcolor=white];
    pr_price [shape=ellipse, label="价格", fillcolor=white];
    pr_region [shape=ellipse, label="地区", fillcolor=white];
    PriceRec -> pr_id [len=0.01];
    PriceRec -> pr_ingid [len=0.01];
    PriceRec -> pr_date [len=0.01];
    PriceRec -> pr_price [len=0.01];
    PriceRec -> pr_region [len=0.01];

    /* 供应商食材关联属性 */
    si_id [shape=ellipse, label=<<U>关联ID</U>>, fillcolor=white];
    si_supid [shape=ellipse, label="供应商ID", fillcolor=white];
    si_ingid [shape=ellipse, label="食材ID", fillcolor=white];
    si_price [shape=ellipse, label="供应单价", fillcolor=white];
    si_primary [shape=ellipse, label="是否主供", fillcolor=white];
    SupIng -> si_id [len=0.01];
    SupIng -> si_supid [len=0.01];
    SupIng -> si_ingid [len=0.01];
    SupIng -> si_price [len=0.01];
    SupIng -> si_primary [len=0.01];

    /* ============================================================
       关系
       ============================================================ */
    R4 [shape=diamond, label="供货", fillcolor="#e3f2fd"];
    Supplier -> R4 [label="1", len=0.3];
    R4 -> Purchase [label="N", len=0.3];

    R5 [shape=diamond, label="产生", fillcolor="#e3f2fd"];
    Canteen -> R5 [label="1", len=0.3];
    R5 -> Purchase [label="N", len=0.3];

    R6 [shape=diamond, label="波动", fillcolor="#e3f2fd"];
    Ingredient -> R6 [label="1", len=0.3];
    R6 -> PriceRec [label="N", len=0.3];

    R7 [shape=diamond, label="供应", fillcolor="#e3f2fd"];
    Supplier -> R7 [label="1", len=0.3];
    R7 -> SupIng [label="N", len=0.3];

    R8 [shape=diamond, label="被供应", fillcolor="#e3f2fd"];
    Ingredient -> R8 [label="1", len=0.3];
    R8 -> SupIng [label="N", len=0.3];

    /* ============================================================
       第五层：采购明细
       ============================================================ */
    PurItem [label="采购明细"];
    pi_id [shape=ellipse, label=<<U>明细ID</U>>, fillcolor=white];
    pi_purid [shape=ellipse, label="采购ID", fillcolor=white];
    pi_ingid [shape=ellipse, label="食材ID", fillcolor=white];
    pi_qty [shape=ellipse, label="数量", fillcolor=white];
    pi_up [shape=ellipse, label="单价", fillcolor=white];
    pi_amt [shape=ellipse, label="金额", fillcolor=white];
    PurItem -> pi_id [len=0.01];
    PurItem -> pi_purid [len=0.01];
    PurItem -> pi_ingid [len=0.01];
    PurItem -> pi_qty [len=0.01];
    PurItem -> pi_up [len=0.01];
    PurItem -> pi_amt [len=0.01];

    R9 [shape=diamond, label="包含", fillcolor="#e3f2fd"];
    Purchase -> R9 [label="1", len=0.3];
    R9 -> PurItem [label="N", len=0.3];

    R10 [shape=diamond, label="采购", fillcolor="#e3f2fd"];
    Ingredient -> R10 [label="1", len=0.3];
    R10 -> PurItem [label="N", len=0.3];

    /* ============================================================
       第六层：业务记录实体（并行排列）
       ============================================================ */
    { rank=same;
      DailyCtrl [label="日管控"];
      WeeklyInsp [label="周排查"];
      MonthlyDisp [label="月调度"];
    }

    /* 日管控属性 */
    dc_id [shape=ellipse, label=<<U>记录ID</U>>, fillcolor=white];
    dc_schid [shape=ellipse, label="学校ID", fillcolor=white];
    dc_canid [shape=ellipse, label="食堂ID", fillcolor=white];
    dc_date [shape=ellipse, label="管控日期", fillcolor=white];
    dc_total [shape=ellipse, label="排查总数", fillcolor=white];
    dc_done [shape=ellipse, label="已完成", fillcolor=white];
    dc_pending [shape=ellipse, label="待整改", fillcolor=white];
    dc_status [shape=ellipse, label="状态", fillcolor=white];
    DailyCtrl -> dc_id [len=0.01];
    DailyCtrl -> dc_schid [len=0.01];
    DailyCtrl -> dc_canid [len=0.01];
    DailyCtrl -> dc_date [len=0.01];
    DailyCtrl -> dc_total [len=0.01];
    DailyCtrl -> dc_done [len=0.01];
    DailyCtrl -> dc_pending [len=0.01];
    DailyCtrl -> dc_status [len=0.01];

    /* 周排查属性 */
    wi_id [shape=ellipse, label=<<U>记录ID</U>>, fillcolor=white];
    wi_schid [shape=ellipse, label="学校ID", fillcolor=white];
    wi_week [shape=ellipse, label="排查周次", fillcolor=white];
    wi_total [shape=ellipse, label="排查总数", fillcolor=white];
    wi_qual [shape=ellipse, label="合格数", fillcolor=white];
    wi_yel [shape=ellipse, label="黄线问题", fillcolor=white];
    wi_bas [shape=ellipse, label="基础问题", fillcolor=white];
    wi_rate [shape=ellipse, label="合格率", fillcolor=white];
    WeeklyInsp -> wi_id [len=0.01];
    WeeklyInsp -> wi_schid [len=0.01];
    WeeklyInsp -> wi_week [len=0.01];
    WeeklyInsp -> wi_total [len=0.01];
    WeeklyInsp -> wi_qual [len=0.01];
    WeeklyInsp -> wi_yel [len=0.01];
    WeeklyInsp -> wi_bas [len=0.01];
    WeeklyInsp -> wi_rate [len=0.01];

    /* 月调度属性 */
    md_id [shape=ellipse, label=<<U>记录ID</U>>, fillcolor=white];
    md_schid [shape=ellipse, label="学校ID", fillcolor=white];
    md_canid [shape=ellipse, label="食堂ID", fillcolor=white];
    md_month [shape=ellipse, label="月份", fillcolor=white];
    md_name [shape=ellipse, label="调度名称", fillcolor=white];
    md_status [shape=ellipse, label="状态", fillcolor=white];
    md_time [shape=ellipse, label="调度时间", fillcolor=white];
    MonthlyDisp -> md_id [len=0.01];
    MonthlyDisp -> md_schid [len=0.01];
    MonthlyDisp -> md_canid [len=0.01];
    MonthlyDisp -> md_month [len=0.01];
    MonthlyDisp -> md_name [len=0.01];
    MonthlyDisp -> md_status [len=0.01];
    MonthlyDisp -> md_time [len=0.01];

    /* 关系 */
    R11 [shape=diamond, label="产生", fillcolor="#e3f2fd"];
    School -> R11 [label="1", len=0.3];
    R11 -> DailyCtrl [label="N", len=0.3];

    R12 [shape=diamond, label="产生", fillcolor="#e3f2fd"];
    Canteen -> R12 [label="1", len=0.3];
    R12 -> DailyCtrl [label="N", len=0.3];

    R13 [shape=diamond, label="产生", fillcolor="#e3f2fd"];
    School -> R13 [label="1", len=0.3];
    R13 -> WeeklyInsp [label="N", len=0.3];

    R14 [shape=diamond, label="产生", fillcolor="#e3f2fd"];
    School -> R14 [label="1", len=0.3];
    R14 -> MonthlyDisp [label="N", len=0.3];

    R15 [shape=diamond, label="产生", fillcolor="#e3f2fd"];
    Canteen -> R15 [label="1", len=0.3];
    R15 -> MonthlyDisp [label="N", len=0.3];

    /* ============================================================
       第七层：日管控明细
       ============================================================ */
    DCItem [label="管控明细"];
    dci_id [shape=ellipse, label=<<U>明细ID</U>>, fillcolor=white];
    dci_dcid [shape=ellipse, label="管控ID", fillcolor=white];
    dci_name [shape=ellipse, label="检查项", fillcolor=white];
    dci_result [shape=ellipse, label="结果", fillcolor=white];
    dci_issue [shape=ellipse, label="问题描述", fillcolor=white];
    dci_rect [shape=ellipse, label="整改状态", fillcolor=white];
    DCItem -> dci_id [len=0.01];
    DCItem -> dci_dcid [len=0.01];
    DCItem -> dci_name [len=0.01];
    DCItem -> dci_result [len=0.01];
    DCItem -> dci_issue [len=0.01];
    DCItem -> dci_rect [len=0.01];

    R16 [shape=diamond, label="包含", fillcolor="#e3f2fd"];
    DailyCtrl -> R16 [label="1", len=0.3];
    R16 -> DCItem [label="N", len=0.3];

    /* ============================================================
       第八层：更多业务记录
       ============================================================ */
    { rank=same;
      Order [label="订餐记录"];
      IngAccept [label="食材验收"];
      Evaluation [label="师生评价"];
    }

    /* 订餐属性 */
    ord_id [shape=ellipse, label=<<U>记录ID</U>>, fillcolor=white];
    ord_schid [shape=ellipse, label="学校ID", fillcolor=white];
    ord_canid [shape=ellipse, label="食堂ID", fillcolor=white];
    ord_date [shape=ellipse, label="订餐日期", fillcolor=white];
    ord_type [shape=ellipse, label="餐次", fillcolor=white];
    ord_dish [shape=ellipse, label="菜品", fillcolor=white];
    ord_stu [shape=ellipse, label="学生订餐数", fillcolor=white];
    ord_tea [shape=ellipse, label="教师订餐数", fillcolor=white];
    ord_total [shape=ellipse, label="总订餐数", fillcolor=white];
    ord_amt [shape=ellipse, label="总金额", fillcolor=white];
    Order -> ord_id [len=0.01];
    Order -> ord_schid [len=0.01];
    Order -> ord_canid [len=0.01];
    Order -> ord_date [len=0.01];
    Order -> ord_type [len=0.01];
    Order -> ord_dish [len=0.01];
    Order -> ord_stu [len=0.01];
    Order -> ord_tea [len=0.01];
    Order -> ord_total [len=0.01];
    Order -> ord_amt [len=0.01];

    /* 食材验收属性 */
    ia_id [shape=ellipse, label=<<U>记录ID</U>>, fillcolor=white];
    ia_schid [shape=ellipse, label="学校ID", fillcolor=white];
    ia_canid [shape=ellipse, label="食堂ID", fillcolor=white];
    ia_ingid [shape=ellipse, label="食材ID", fillcolor=white];
    ia_date [shape=ellipse, label="验收日期", fillcolor=white];
    ia_batch [shape=ellipse, label="批次号", fillcolor=white];
    ia_qty [shape=ellipse, label="数量", fillcolor=white];
    ia_qual [shape=ellipse, label="质量状态", fillcolor=white];
    ia_rate [shape=ellipse, label="合格率", fillcolor=white];
    ia_ins [shape=ellipse, label="验收人", fillcolor=white];
    IngAccept -> ia_id [len=0.01];
    IngAccept -> ia_schid [len=0.01];
    IngAccept -> ia_canid [len=0.01];
    IngAccept -> ia_ingid [len=0.01];
    IngAccept -> ia_date [len=0.01];
    IngAccept -> ia_batch [len=0.01];
    IngAccept -> ia_qty [len=0.01];
    IngAccept -> ia_qual [len=0.01];
    IngAccept -> ia_rate [len=0.01];
    IngAccept -> ia_ins [len=0.01];

    /* 师生评价属性 */
    ev_id [shape=ellipse, label=<<U>记录ID</U>>, fillcolor=white];
    ev_schid [shape=ellipse, label="学校ID", fillcolor=white];
    ev_canid [shape=ellipse, label="食堂ID", fillcolor=white];
    ev_date [shape=ellipse, label="评价日期", fillcolor=white];
    ev_type [shape=ellipse, label="评价者类型", fillcolor=white];
    ev_score [shape=ellipse, label="评分", fillcolor=white];
    ev_content [shape=ellipse, label="评价内容", fillcolor=white];
    ev_dish [shape=ellipse, label="评价菜品", fillcolor=white];
    Evaluation -> ev_id [len=0.01];
    Evaluation -> ev_schid [len=0.01];
    Evaluation -> ev_canid [len=0.01];
    Evaluation -> ev_date [len=0.01];
    Evaluation -> ev_type [len=0.01];
    Evaluation -> ev_score [len=0.01];
    Evaluation -> ev_content [len=0.01];
    Evaluation -> ev_dish [len=0.01];

    /* 关系 */
    R17 [shape=diamond, label="产生", fillcolor="#e3f2fd"];
    School -> R17 [label="1", len=0.3];
    R17 -> Order [label="N", len=0.3];

    R18 [shape=diamond, label="产生", fillcolor="#e3f2fd"];
    Canteen -> R18 [label="1", len=0.3];
    R18 -> Order [label="N", len=0.3];

    R19 [shape=diamond, label="产生", fillcolor="#e3f2fd"];
    School -> R19 [label="1", len=0.3];
    R19 -> IngAccept [label="N", len=0.3];

    R20 [shape=diamond, label="产生", fillcolor="#e3f2fd"];
    Canteen -> R20 [label="1", len=0.3];
    R20 -> IngAccept [label="N", len=0.3];

    R21 [shape=diamond, label="验收", fillcolor="#e3f2fd"];
    Ingredient -> R21 [label="1", len=0.3];
    R21 -> IngAccept [label="N", len=0.3];

    R22 [shape=diamond, label="产生", fillcolor="#e3f2fd"];
    School -> R22 [label="1", len=0.3];
    R22 -> Evaluation [label="N", len=0.3];

    R23 [shape=diamond, label="产生", fillcolor="#e3f2fd"];
    Canteen -> R23 [label="1", len=0.3];
    R23 -> Evaluation [label="N", len=0.3];

    /* ============================================================
       第九层：学生营养、消费记录
       ============================================================ */
    { rank=same;
      StuNutri [label="学生营养"];
      Consump [label="消费记录"];
    }

    /* 学生营养属性 */
    sn_id [shape=ellipse, label=<<U>记录ID</U>>, fillcolor=white];
    sn_schid [shape=ellipse, label="学校ID", fillcolor=white];
    sn_canid [shape=ellipse, label="食堂ID", fillcolor=white];
    sn_date [shape=ellipse, label="记录日期", fillcolor=white];
    sn_ingid [shape=ellipse, label="食材ID", fillcolor=white];
    sn_cat [shape=ellipse, label="营养类别", fillcolor=white];
    sn_intake [shape=ellipse, label="摄入量", fillcolor=white];
    sn_prot [shape=ellipse, label="蛋白质", fillcolor=white];
    sn_fat [shape=ellipse, label="脂肪", fillcolor=white];
    sn_carb [shape=ellipse, label="碳水", fillcolor=white];
    sn_cal [shape=ellipse, label="热量", fillcolor=white];
    StuNutri -> sn_id [len=0.01];
    StuNutri -> sn_schid [len=0.01];
    StuNutri -> sn_canid [len=0.01];
    StuNutri -> sn_date [len=0.01];
    StuNutri -> sn_ingid [len=0.01];
    StuNutri -> sn_cat [len=0.01];
    StuNutri -> sn_intake [len=0.01];
    StuNutri -> sn_prot [len=0.01];
    StuNutri -> sn_fat [len=0.01];
    StuNutri -> sn_carb [len=0.01];
    StuNutri -> sn_cal [len=0.01];

    /* 消费记录属性 */
    cr_id [shape=ellipse, label=<<U>记录ID</U>>, fillcolor=white];
    cr_schid [shape=ellipse, label="学校ID", fillcolor=white];
    cr_canid [shape=ellipse, label="食堂ID", fillcolor=white];
    cr_month [shape=ellipse, label="月份", fillcolor=white];
    cr_amt [shape=ellipse, label="消费金额", fillcolor=white];
    cr_count [shape=ellipse, label="消费人次", fillcolor=white];
    cr_avg [shape=ellipse, label="人均消费", fillcolor=white];
    cr_region [shape=ellipse, label="区域", fillcolor=white];
    Consump -> cr_id [len=0.01];
    Consump -> cr_schid [len=0.01];
    Consump -> cr_canid [len=0.01];
    Consump -> cr_month [len=0.01];
    Consump -> cr_amt [len=0.01];
    Consump -> cr_count [len=0.01];
    Consump -> cr_avg [len=0.01];
    Consump -> cr_region [len=0.01];

    /* 关系 */
    R24 [shape=diamond, label="产生", fillcolor="#e3f2fd"];
    School -> R24 [label="1", len=0.3];
    R24 -> StuNutri [label="N", len=0.3];

    R25 [shape=diamond, label="产生", fillcolor="#e3f2fd"];
    Canteen -> R25 [label="1", len=0.3];
    R25 -> StuNutri [label="N", len=0.3];

    R26 [shape=diamond, label="摄入", fillcolor="#e3f2fd"];
    Ingredient -> R26 [label="1", len=0.3];
    R26 -> StuNutri [label="N", len=0.3];

    R27 [shape=diamond, label="产生", fillcolor="#e3f2fd"];
    School -> R27 [label="1", len=0.3];
    R27 -> Consump [label="N", len=0.3];

    R28 [shape=diamond, label="产生", fillcolor="#e3f2fd"];
    Canteen -> R28 [label="1", len=0.3];
    R28 -> Consump [label="N", len=0.3];
}
```

---

## 图例说明

| 符号 | 含义 |
|------|------|
| ▭ 矩形 | **实体**（表） |
| ◯ 椭圆 | **属性**（字段），其中<u>下划线</u>表示**主键** |
| ◇ 菱形 | **关系**（实体间的关联） |
| 连线上的 **1 / N / M** | **基数**（一对一 / 一对多 / 多对多） |

---

## 核心关系汇总

| 关系名 | 实体A | 基数 | 实体B | 说明 |
|--------|-------|------|-------|------|
| 包含 | 区域 | 1:N | 学校 | 一个区域包含多所学校 |
| 拥有 | 学校 | 1:N | 食堂 | 一所学校拥有多个食堂 |
| 分类 | 食材分类 | 1:N | 食材 | 一个分类下有多种食材 |
| 供货 | 供应商 | 1:N | 采购订单 | 一个供应商参与多次采购 |
| 产生 | 学校/食堂 | 1:N | 采购订单 | 学校/食堂产生多条采购记录 |
| 产生 | 学校/食堂 | 1:N | 订餐记录 | 学校/食堂产生多条订餐记录 |
| 产生 | 学校/食堂 | 1:N | 日管控 | 学校/食堂产生多条管控记录 |
| 包含 | 日管控 | 1:N | 管控明细 | 一条管控记录包含多条检查项 |
| 产生 | 学校 | 1:N | 周排查 | 学校产生多条周排查记录 |
| 产生 | 学校/食堂 | 1:N | 月调度 | 学校/食堂产生多条月调度记录 |
| 产生 | 学校/食堂 | 1:N | 食材验收 | 学校/食堂产生多条验收记录 |
| 验收 | 食材 | 1:N | 食材验收 | 一种食材被验收多次 |
| 产生 | 学校/食堂 | 1:N | 师生评价 | 学校/食堂产生多条评价记录 |
| 产生 | 学校/食堂 | 1:N | 学生营养 | 学校/食堂产生多条营养记录 |
| 摄入 | 食材 | 1:N | 学生营养 | 一种食材对应多条营养记录 |
| 产生 | 学校/食堂 | 1:N | 消费记录 | 学校/食堂产生多条消费记录 |
| 包含 | 采购订单 | 1:N | 采购明细 | 一张采购订单包含多条明细 |
| 采购 | 食材 | 1:N | 采购明细 | 一种食材被多次采购 |
| 波动 | 食材 | 1:N | 食材价格 | 一种食材有多条价格记录 |
| 供应 | 供应商 | 1:N | 供应商食材关联 | 一个供应商供应多种食材 |
| 被供应 | 食材 | 1:N | 供应商食材关联 | 一种食材可被多个供应商供应 |

---

## 渲染方法

### 方法1：VS Code + image2 插件

1. 将上方 DOT 代码保存为 `er.dot` 文件
2. 在 VS Code 中打开该文件
3. 按 `Ctrl+Shift+V`（或右键 → image2: Preview）即可预览 ER 图
4. 右键图片选择 "Save Image" 保存为 PNG

### 方法2：Graphviz 命令行

```bash
# 安装 Graphviz（如未安装）
# Windows: winget install Graphviz
# macOS: brew install graphviz
# Ubuntu: sudo apt install graphviz

# 生成 PNG 图片
dot -Tpng er.dot -o er-diagram.png

# 生成 SVG 矢量图
dot -Tsvg er.dot -o er-diagram.svg

# 生成 PDF
dot -Tpdf er.dot -o er-diagram.pdf
```

### 方法3：在线工具

- https://dreampuf.github.io/GraphvizOnline/ （粘贴 DOT 代码实时预览）
- https://edotor.net/ （支持导出多种格式）

---

## 实体与字段总表

### 基础档案实体

| 实体 | 主键 | 主要属性 |
|------|------|----------|
| **区域** | 区域ID | 区域名称、区域编码、父区域ID |
| **学校** | 学校ID | 区域ID、学校名称、学校编码、学校类型、地址、学生人数、教师人数 |
| **食堂** | 食堂ID | 学校ID、食堂名称、食堂编码、食堂类型、负责人、容纳人数 |
| **供应商** | 供应商ID | 供应商名称、供应商编码、联系人、联系电话、地址、综合评分、评级、状态 |
| **食材分类** | 分类ID | 分类名称、分类编码、父分类ID |
| **食材** | 食材ID | 分类ID、食材名称、食材编码、规格、计量单位 |

### 业务记录实体

| 实体 | 主键 | 主要属性 |
|------|------|----------|
| **采购订单** | 采购ID | 学校ID、食堂ID、供应商ID、订单编号、采购日期、总金额、已支付、待支付、支付状态 |
| **采购明细** | 明细ID | 采购ID、食材ID、数量、单价、金额 |
| **食材价格** | 记录ID | 食材ID、记录日期、价格、地区 |
| **订餐记录** | 记录ID | 学校ID、食堂ID、订餐日期、餐次、菜品、学生订餐数、教师订餐数、总订餐数、总金额 |
| **日管控** | 记录ID | 学校ID、食堂ID、管控日期、排查总数、已完成、待整改、状态 |
| **管控明细** | 明细ID | 管控ID、检查项、结果、问题描述、整改状态 |
| **周排查** | 记录ID | 学校ID、排查周次、排查总数、合格数、黄线问题、基础问题、合格率 |
| **月调度** | 记录ID | 学校ID、食堂ID、月份、调度名称、状态、调度时间 |
| **食材验收** | 记录ID | 学校ID、食堂ID、食材ID、验收日期、批次号、数量、质量状态、合格率、验收人 |
| **师生评价** | 记录ID | 学校ID、食堂ID、评价日期、评价者类型、评分、评价内容、评价菜品 |
| **学生营养** | 记录ID | 学校ID、食堂ID、记录日期、食材ID、营养类别、摄入量、蛋白质、脂肪、碳水、热量 |
| **消费记录** | 记录ID | 学校ID、食堂ID、月份、消费金额、消费人次、人均消费、区域 |

### 关联实体

| 实体 | 主键 | 主要属性 |
|------|------|----------|
| **供应商食材关联** | 关联ID | 供应商ID、食材ID、供应单价、是否主供 |

---

## 大屏板块与实体对应

| 大屏板块 | 来源实体 | 统计维度 |
|----------|----------|----------|
| 日管控情况汇总 | 日管控 + 管控明细 | 按日期统计排查/完成/待整改数量 |
| 采购总成本分析 | 采购订单 + 采购明细 | 按支付状态汇总金额 |
| 膳食经费数据分析 | 采购订单 | 按月统计采购总金额 |
| 订餐数据分析 | 订餐记录 | 按菜品/餐次统计订餐数量 |
| 区域数据分布 | 学校 + 订餐记录 | 按区域聚合订单数 |
| 食材单价波动分析 | 食材价格 | 按日期统计食材价格趋势 |
| 学生营养情况分析 | 学生营养 | 按营养类别统计摄入量 |
| 供应商评分分析 | 供应商 | 直接展示评分与评级 |
| 月调度情况汇总 | 月调度 | 按月展示调度列表 |
| 周排查情况汇总 | 周排查 | 统计排查/合格/问题数量 |
| 消费数据分析 | 消费记录 | 按月/区域统计消费金额 |
| 食材验收质量分析 | 食材验收 | 按质量状态统计合格率分布 |
| 师生评价情况分析 | 师生评价 | 按日期统计平均分、展示评价内容 |
