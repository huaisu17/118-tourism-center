# 智校数据综合展示中心 — 各实体独立属性图

> 本文档为每个实体单独生成一张 ER 子图，只显示该实体及其属性，不显示关系。用于汇报时逐个介绍实体字段。
> 不包含**区域**和**学校**实体。

---

## 渲染方法

每张图都可以单独保存为 `.dot` 文件后渲染：

```bash
dot -Tpng 食堂.dot -o 食堂.png
```

或者在 VS Code 中安装 image2 插件，按 `Ctrl+Shift+V` 预览。

---

## 1. 食堂

```dot
digraph ER_Canteen {
    graph [fontname="Microsoft YaHei", fontsize=12, rankdir=TB,
           bgcolor=white, nodesep=0.4, ranksep=0.8];
    node [fontname="Microsoft YaHei", fontsize=10,
          shape=rectangle, style=filled, fillcolor="#f8f9fa",
          width=0, height=0, margin="0.15,0.08"];
    edge [fontname="Microsoft YaHei", fontsize=9,
          arrowhead=none, color="#333333"];

    Canteen [label="食堂"];
    can_id [shape=ellipse, label="食堂ID", fillcolor=white];
    can_name [shape=ellipse, label="食堂名称", fillcolor=white];
    can_type [shape=ellipse, label="食堂类型", fillcolor=white];

    Canteen -> can_id [len=0.01];
    Canteen -> can_name [len=0.01];
    Canteen -> can_type [len=0.01];
}
```

---

## 2. 供应商

```dot
digraph ER_Supplier {
    graph [fontname="Microsoft YaHei", fontsize=12, rankdir=TB,
           bgcolor=white, nodesep=0.4, ranksep=0.8];
    node [fontname="Microsoft YaHei", fontsize=10,
          shape=rectangle, style=filled, fillcolor="#f8f9fa",
          width=0, height=0, margin="0.15,0.08"];
    edge [fontname="Microsoft YaHei", fontsize=9,
          arrowhead=none, color="#333333"];

    Supplier [label="供应商"];
    sup_id [shape=ellipse, label="供应商ID", fillcolor=white];
    sup_name [shape=ellipse, label="供应商名称", fillcolor=white];
    sup_score [shape=ellipse, label="综合评分", fillcolor=white];

    Supplier -> sup_id [len=0.01];
    Supplier -> sup_name [len=0.01];
    Supplier -> sup_score [len=0.01];
}
```

---
## 3. 食材分类

```dot
digraph ER_IngCat {
    graph [fontname="Microsoft YaHei", fontsize=12, rankdir=TB,
           bgcolor=white, nodesep=0.4, ranksep=0.8];
    node [fontname="Microsoft YaHei", fontsize=10,
          shape=rectangle, style=filled, fillcolor="#f8f9fa",
          width=0, height=0, margin="0.15,0.08"];
    edge [fontname="Microsoft YaHei", fontsize=9,
          arrowhead=none, color="#333333"];

    IngCat [label="食材分类"];
    cat_id [shape=ellipse, label="分类ID", fillcolor=white];
    cat_name [shape=ellipse, label="分类名称", fillcolor=white];

    IngCat -> cat_id [len=0.01];
    IngCat -> cat_name [len=0.01];
}
```

---

## 4. 食材

```dot
digraph ER_Ingredient {
    graph [fontname="Microsoft YaHei", fontsize=12, rankdir=TB,
           bgcolor=white, nodesep=0.4, ranksep=0.8];
    node [fontname="Microsoft YaHei", fontsize=10,
          shape=rectangle, style=filled, fillcolor="#f8f9fa",
          width=0, height=0, margin="0.15,0.08"];
    edge [fontname="Microsoft YaHei", fontsize=9,
          arrowhead=none, color="#333333"];

    Ingredient [label="食材"];
    ing_id [shape=ellipse, label="食材ID", fillcolor=white];
    ing_name [shape=ellipse, label="食材名称", fillcolor=white];
    ing_unit [shape=ellipse, label="计量单位", fillcolor=white];

    Ingredient -> ing_id [len=0.01];
    Ingredient -> ing_name [len=0.01];
    Ingredient -> ing_unit [len=0.01];
}
```

---

## 5. 采购订单

```dot
digraph ER_Purchase {
    graph [fontname="Microsoft YaHei", fontsize=12, rankdir=TB,
           bgcolor=white, nodesep=0.4, ranksep=0.8];
    node [fontname="Microsoft YaHei", fontsize=10,
          shape=rectangle, style=filled, fillcolor="#f8f9fa",
          width=0, height=0, margin="0.15,0.08"];
    edge [fontname="Microsoft YaHei", fontsize=9,
          arrowhead=none, color="#333333"];

    Purchase [label="采购订单"];
    pur_id [shape=ellipse, label="采购ID", fillcolor=white];
    pur_date [shape=ellipse, label="采购日期", fillcolor=white];
    pur_total [shape=ellipse, label="总金额", fillcolor=white];

    Purchase -> pur_id [len=0.01];
    Purchase -> pur_date [len=0.01];
    Purchase -> pur_total [len=0.01];
}
```

---

## 6. 采购明细

```dot
digraph ER_PurItem {
    graph [fontname="Microsoft YaHei", fontsize=12, rankdir=TB,
           bgcolor=white, nodesep=0.4, ranksep=0.8];
    node [fontname="Microsoft YaHei", fontsize=10,
          shape=rectangle, style=filled, fillcolor="#f8f9fa",
          width=0, height=0, margin="0.15,0.08"];
    edge [fontname="Microsoft YaHei", fontsize=9,
          arrowhead=none, color="#333333"];

    PurItem [label="采购明细"];
    pi_id [shape=ellipse, label="明细ID", fillcolor=white];
    pi_qty [shape=ellipse, label="数量", fillcolor=white];
    pi_up [shape=ellipse, label="单价", fillcolor=white];

    PurItem -> pi_id [len=0.01];
    PurItem -> pi_qty [len=0.01];
    PurItem -> pi_up [len=0.01];
}
```

---

## 7. 食材验收

```dot
digraph ER_IngAccept {
    graph [fontname="Microsoft YaHei", fontsize=12, rankdir=TB,
           bgcolor=white, nodesep=0.4, ranksep=0.8];
    node [fontname="Microsoft YaHei", fontsize=10,
          shape=rectangle, style=filled, fillcolor="#f8f9fa",
          width=0, height=0, margin="0.15,0.08"];
    edge [fontname="Microsoft YaHei", fontsize=9,
          arrowhead=none, color="#333333"];

    IngAccept [label="食材验收"];
    ia_id [shape=ellipse, label="验收ID", fillcolor=white];
    ia_date [shape=ellipse, label="验收日期", fillcolor=white];
    ia_qual [shape=ellipse, label="质量状态", fillcolor=white];

    IngAccept -> ia_id [len=0.01];
    IngAccept -> ia_date [len=0.01];
    IngAccept -> ia_qual [len=0.01];
}
```

---

## 批量渲染脚本

把上面的代码分别保存为 `食堂.dot`、`供应商.dot`、`食材分类.dot`、`食材.dot`、`采购订单.dot`、`采购明细.dot`、`食材验收.dot`，然后运行：

```bash
for f in 食堂 供应商 食材分类 食材 采购订单 采购明细 食材验收; do
    dot -Tpng "$f.dot" -o "$f.png"
done
```
