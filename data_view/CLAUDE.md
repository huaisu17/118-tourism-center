# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a single-page data visualization dashboard ("智校数据综合展示中心") for campus catering and logistics data. It was adapted from a livestock/agriculture trading dashboard template. The page is designed for 3840x2160 (4K) display and uses a JS scale transform to fit the viewport.

**Tech stack:** Plain HTML/CSS/JS (no build step, no bundler, no TypeScript). jQuery + ECharts 4.x for charts. Vue 2 is loaded but unused.

## Development Commands

- `npm run build` — Copies `index.html`, `cs/`, `js/`, `images/`, and `广东省.svg` into `dist/`. This is a simple file copy; there is no compilation or bundling.
- To develop, open `index.html` directly in a browser. There is no dev server, hot reload, or watcher.
- No lint, test, or type-check commands exist.

## Architecture

### Page Structure

The page is a fixed-layout dashboard with a single scaling container (`.content`, 3840x2160px):

- **Header** (`#head`): Title, live clock, and weather info.
- **Body** (`#body`): Three columns
  - **Left** (`.bodyLeft`): Two stacked sections containing 4 data blocks total.
  - **Center** (`.bodyMiddle`): Map area with three tabs (national map, Guangdong SVG map, market trend).
  - **Right** (`.bodyRight`): Two stacked sections containing 4 data blocks total.
- **Footer** (`#foot`): Four horizontal data blocks.

Excluding the center map, there are **12 titled data blocks**. Their current titles (already renamed to campus context) are:

1. 日管控情况汇总 (top-left, with bar chart)
2. 采购总成本分析 (top-left inner ring chart)
3. 膳食经费数据分析 (mid-left pie chart)
4. 订餐数据分析 (mid-left-right horizontal bar)
5. 食材单价波动分析 (top-right-left line chart)
6. 学生营养情况分析 (top-right-right stacked bar)
7. 供应商评分分析 (bottom-right-left ring chart)
8. 月调度情况汇总 (bottom-right-right scrolling list)
9. 周排查情况汇总 (foot-left-1, four gauge cards)
10. 消费数据分析 (foot-left-2, multi-line chart)
11. 食材验收质量分析 (foot-left-3, pie chart + category stats)
12. 师生评价情况分析 (foot-left-4, bar chart)

### File Responsibilities

- `index.html` — Page structure and chart container divs. Each block has a `.childtitle` heading and a container div for the ECharts instance.
- `cs/index.css` — All styles. Uses absolute/relative positioning for the fixed layout. Background image `beijing.png` is loaded from `images/`.
- `js/index.js` — All chart initialization and interaction logic (~2500 lines). Contains one large function per chart that creates an ECharts instance and calls `.setOption()`. Also handles the map tabs, scrolling lists, and modal zoom (`Show()` function).
- `js/data.js` — Static mock data arrays consumed by `index.js` (e.g., `DataCenter`, `ChanNeng`, `RZstatus`).
- `js/echarts.min.js`, `js/jquery.js`, `js/china.js`, `js/can.js`, `js/beihai.js` — Third-party libraries. `china.js` provides the national map geoJSON. `beihai.js` contains SVG markup for the Guangdong regional map.
- `接口规划.md` — Documents planned backend API contracts (13 endpoints) for when this moves from static mock data to real data. Unified response envelope: `{code, message, success, data, serverTime, requestId}`.

### Data Flow

Currently static: `js/data.js` defines arrays → `js/index.js` reads them during chart initialization. Some charts originally had `setInterval` random refreshes; several have been removed to stabilize the display. If you replace data with fixed values, check for and remove any remaining `setInterval` refreshes in `index.js` so they do not overwrite your changes.

### Critical Constraints

- **Do not restructure the layout.** The current HTML/CSS structure is fragile and has been hand-tuned. The user previously adjusted positions manually (especially the bottom-right ring chart and scrolling list).
- **Do not attempt to float blocks over the map.** Multiple prior attempts broke the page; this approach has been abandoned.
- **Prefer changing data and chart options over changing HTML structure.** The page is in a stable intermediate state: titles and most data have been switched to campus semantics, but not every chart visually matches the target design (`goal.png`).
- **File encoding is UTF-8.** On Windows, PowerShell may show garbled characters; always read/write with explicit UTF-8.

## Chart Functions in `js/index.js`

Key chart init functions (search for these names):

- `guapai(obj)` — 日管控情况汇总 bar chart
- `guapaizhanbi(obj, Index)` — 采购总成本分析 ring chart
- Charts around line 1284 — 膳食经费数据分析 (pie), 食材单价波动分析 (line), 学生营养情况分析 (stacked bar)
- `echarts.init($("#cp")[0])` — 订餐数据分析 horizontal bar
- `echarts.init($("#jiage")[0])` — 消费数据分析 multi-line
- `echarts.init($("#CJpie")[0])` — 食材验收质量分析 pie
- `echarts.init($("#cjliang")[0])` — 师生评价情况分析 bar
- `YB(id, names, datas)` — Gauge charts for 周排查情况汇总
- `renderRegionalMap(svgMarkup)` — Guangdong SVG map rendering with elevation-based coloring and hover effects

## Build Output

`dist/` contains the copied static files and is meant to be served as a static site.
