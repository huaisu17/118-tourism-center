# CODEx 交付文档

项目路径：
`D:\school_work\big-data-view\web\118 旅游智慧中心`

本次主要改动文件：
- `草牧商品交易数据可视化大屏HTML模板/index.html`
- `草牧商品交易数据可视化大屏HTML模板/cs/index.css`
- `草牧商品交易数据可视化大屏HTML模板/js/index.js`

## 当前用户目标背景

本轮主要在处理两个区域：

1. 左上 `数据统计分析` 右侧区域
2. 右上 `游客趋势 / 景点热度排行` 区域

以及曾经改动过但后来被用户要求删除图表的 `数据变化趋势` 区域。

## 已完成内容

### 1. 右上数据块已经改成双图结构

`草牧商品交易数据可视化大屏HTML模板/index.html`

当前右上块保留两张图：
- 左：`游客趋势`
- 右：`景点热度排行`

外层原来的总标题“实时数据监控”已经删掉，只保留和图表直接对应的两个子标题。

### 2. 右上右侧图已经改成原生 ECharts 横向排行图

`草牧商品交易数据可视化大屏HTML模板/js/index.js`

当前 `#right-top-compare` 使用的是原生横向排名图，不再使用之前那版弧形图/自定义图例/自定义交互。

相关变量：
- `scenicSpotNames`
- `scenicSpotValues`
- `scenicSpotBarColors`

### 3. 右上左侧“游客趋势”图为避开标题，已调整 grid

当前 `rightTopOption.grid` 关键参数是：

```js
top: 72,
left: 86,
right: 12,
bottom: 32
```

这是因为用户明确要求：
- 不要动 title
- 只动图表本身

所以这里是通过调 `grid.top / left / bottom` 来避开标题和坐标轴遮挡。

### 4. 左上右侧区域已经只保留环形图

`草牧商品交易数据可视化大屏HTML模板/index.html`

原来的横向条形块 `#left-top-right` 已经从结构里删掉，现在只剩：
- 小标题：`交割环比分析`
- 环形图容器：`#left-top-right-circle`

### 5. “数据变化趋势”里的原图已删

`草牧商品交易数据可视化大屏HTML模板/index.html`

这个块里原来的 `#jiagezoushi` 容器已经删掉。

`草牧商品交易数据可视化大屏HTML模板/js/index.js`

对应价格走势那段 JS 没有完整物理删除，但已经加了空节点保护：

```js
var chartDom = $("#jiagezoushi")[0];
if (!chartDom) {
    return;
}
```

所以现在不会因为 DOM 不存在而报错。

## 当前实际状态

### index.html

当前关键结构：

- 左上：
  - `数据统计分析`
  - `交割环比分析`
  - `#left-top-right-circle`

- 右上：
  - `游客趋势`
  - `#right-top-bottom`
  - `景点热度排行`
  - `#right-top-compare`

- 右下：
  - 主标题 `数据变化趋势`
  - 额外新增了一个小标题 `价格走势分析`
  - 但这个块里已经没有图表容器了

也就是说：
`价格走势分析` 这个小标题现在只是标题，没有图表内容。

### cs/index.css

当前几个特别需要注意的值：

```css
.bodyLeftTop .leftTopCircleTitle{ position:absolute; left:-200px; top:10px; z-index:2; }
#left-top-right { display:none; }
#left-top-right-circle { width:540px;height:422px;position: absolute;left:0;top:100px; }

.bodyRightTopBG{ margin-left:8px; width:1248px; height:536px; background:url("../images/001.png") no-repeat; background-size:100% 100%; }
.bodyRightTopBG #right-top-bottom{ width:100%; height:492px; margin-top:40px; margin-left:0; }
.bodyRightTopBG #right-top-compare{ width:100%; height:476px; margin-top:56px; }

.bodyRightBottom .rightBottomSideTitle{ position:absolute; left:650px; top:12px; z-index:2; }
```

这里面有两个明显异常/需确认项：

1. `.leftTopCircleTitle` 当前是 `left:-200px`
这不是我推荐保留的正常值，属于调位置过程中用户/会话里残留出来的结果。下一个 Codex 需要结合页面实际效果确认是否合理。

2. `.rightBottomSideTitle` 当前是 `left:650px`
这个标题现在虽然可以移动，但因为右下图表已经删了，所以它现在只是一个额外标题，是否保留要看用户下一步意图。

### js/index.js

当前右上两张图逻辑都还在：

- `rightTopChart` 对应 `#right-top-bottom`
- `rightTopCompareChart` 对应 `#right-top-compare`

右下价格走势图逻辑被“软删除”：
- DOM 不存在时直接 return

## 当前已知问题 / 风险点

### 1. 左上 `交割环比分析` 标题位置很可能不正常

`left:-200px` 极不自然，虽然用户最后没有继续追这个位置，但这大概率不是最终想要的值。

### 2. 右下 `数据变化趋势` 区域目前是半空状态

现在这个块有：
- 主标题 `数据变化趋势`
- 小标题 `价格走势分析`

但没有任何图表内容。

如果用户继续看这个区域，很可能会要求：
- 删掉这个小标题
- 或者干脆删掉整个空块
- 或者补一个新的简单图表

### 3. 右上图表尺寸是通过硬编码高度和 grid 调出来的

如果用户继续要求“更贴边”“更大”“坐标不要挡标题”，优先改：

#### 游客趋势
- `cs/index.css` 里的 `#right-top-bottom`
- `js/index.js` 里的 `rightTopOption.grid`

#### 景点热度排行
- `cs/index.css` 里的 `#right-top-compare`
- `js/index.js` 里的 `rightTopCompareOption.grid`

### 4. 仓库里存在异常路径警告

执行 `git status` 时会看到：

```text
warning: could not open directory '”./': No such file or directory
```

这不是本轮造成的，但说明仓库里可能存在异常目录引用或历史残留路径问题。

### 5. 不要再碰编码转换

这个仓库之前已经出过编码问题。
尤其是：
- `index.html`
- `index.css`
- `index.js`

如果不是必要，不要做批量转码。
如果必须改，优先做局部改动。

## 建议下一位 Codex 的第一步

1. 先打开页面确认这三个区域的最终视觉状态：
   - 左上 `交割环比分析`
   - 右上 `游客趋势 / 景点热度排行`
   - 右下 `数据变化趋势`

2. 重点确认下面两个残留是否还要保留：
   - `.leftTopCircleTitle{ left:-200px; ... }`
   - `.bodyRightBottom .rightBottomSideTitle`

3. 如果用户下一步继续改“数据变化趋势”，建议先问清：
   - 是要删掉整个空块
   - 还是只删掉额外小标题
   - 还是重新放一个新图

## 当前 git 状态

当前修改中的文件只有这 3 个：
- `草牧商品交易数据可视化大屏HTML模板/index.html`
- `草牧商品交易数据可视化大屏HTML模板/cs/index.css`
- `草牧商品交易数据可视化大屏HTML模板/js/index.js`

没有帮用户做 commit。
