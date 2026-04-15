# 快速参考指南

## 🎯 改造目标
将"云南全域智慧旅游智慧中心"从传统数据看板风格升级为科技感指挥中心风格。

## 📋 改造内容

### 1️⃣ HTML 结构 (`index.html`)
```
dashboard
├── topbar (顶部标题栏)
└── screen (主屏幕)
    ├── panel.panel-left (左侧面板)
    ├── stage (中间舞台)
    └── panel.panel-right (右侧面板)
```

### 2️⃣ CSS 主题 (`static/style.css`)

**核心颜色变量：**
```css
--cyan: #35d8ff;           /* 主色 - 青蓝 */
--gold: #f7c35f;           /* 强调色 - 金黄 */
--text: #d9f3ff;           /* 文字 - 亮蓝 */
--text-dim: #7fa9c9;       /* 暗文字 - 蓝灰 */
```

**卡片样式：**
- 背景：深蓝渐变
- 边框：青蓝色，半透明
- 阴影：发光效果
- Hover：边框发光，向上浮起

### 3️⃣ 图表主题 (`static/chart.js`)

**统一配置：**
```javascript
chartTheme = {
  colors: ['#35d8ff', '#f7c35f', '#ff4757', '#ffa502', '#7be7ff', '#a181fc'],
  textColor: '#d9f3ff',
  gridColor: 'rgba(58, 181, 255, 0.1)',
}
```

**应用到：**
- barChart() - 柱状图
- consumptionChart() - 饼图
- lineChart() - 折线图

## 🔧 如何修改

### 修改卡片样式
编辑 `static/style.css` 中的 `.card` 类：
```css
.card {
  background: linear-gradient(...);
  border: 1px solid var(--panel-border);
  box-shadow: ...;
}
```

### 修改图表颜色
编辑 `static/chart.js` 中的 `chartTheme` 对象：
```javascript
const chartTheme = {
  colors: ['#35d8ff', '#f7c35f', ...],
  ...
}
```

### 修改布局比例
编辑 `static/style.css` 中的 `.screen`：
```css
.screen {
  grid-template-columns: 1fr 1.8fr 1fr;  /* 左 中 右 */
}
```

## 📊 布局结构

```
┌─────────────────────────────────────────────┐
│           顶部标题栏 (topbar)               │
├──────────┬──────────────────┬──────────────┤
│          │                  │              │
│  左侧    │    中间舞台      │   右侧       │
│  面板    │   (地图主视觉)   │   面板       │
│  26%     │      48%         │   26%        │
│          │                  │              │
└──────────┴──────────────────┴──────────────┘
```

## 🎨 颜色速查表

| 用途 | 颜色 | 十六进制 |
|------|------|---------|
| 主色 | 青蓝 | #35d8ff |
| 强调 | 金黄 | #f7c35f |
| 风险 | 红色 | #ff4757 |
| 警告 | 橙色 | #ffa502 |
| 文字 | 亮蓝 | #d9f3ff |
| 暗文 | 蓝灰 | #7fa9c9 |
| 背景 | 深蓝 | #07162d |

## 🚀 快速开始

1. **查看效果**
   - 在浏览器中打开 `index.html`
   - 检查三列布局是否正确

2. **调整样式**
   - 编辑 `static/style.css`
   - 修改颜色变量或卡片样式
   - 刷新浏览器查看效果

3. **修改图表**
   - 编辑 `static/chart.js`
   - 修改 `chartTheme` 对象
   - 刷新浏览器查看效果

## 📝 文件清单

| 文件 | 用途 | 修改状态 |
|------|------|--------|
| `index.html` | 页面结构 | ✅ 已改造 |
| `static/style.css` | 样式表 | ✅ 已改造 |
| `static/chart.js` | 图表配置 | ✅ 已改造 |
| `static/index.js` | 初始化脚本 | ⚪ 无需改 |
| `static/common.css` | 通用样式 | ⚪ 无需改 |

## 💡 常见问题

**Q: 如何改变卡片的发光颜色？**
A: 修改 `static/style.css` 中的 `--panel-border` 变量。

**Q: 如何改变图表的主色？**
A: 修改 `static/chart.js` 中 `chartTheme.colors` 数组的第一个颜色。

**Q: 如何调整左右面板的宽度？**
A: 修改 `static/style.css` 中 `.screen` 的 `grid-template-columns` 值。

**Q: 如何禁用卡片的 Hover 效果？**
A: 在 `static/style.css` 中注释掉 `.card:hover` 规则。

## 🔗 相关文件

- 改造方案：`work.md`
- 改造总结：`REFACTOR_SUMMARY.md`
- 目标设计：`goal.png`

---

**最后更新：2024年**
**改造状态：✅ 完成**
