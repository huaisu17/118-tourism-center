# 🚀 如何打开和查看项目

## 方法 1：直接在浏览器中打开（推荐）

### Windows
```bash
# 方法 A：直接双击
1. 打开文件管理器
2. 导航到：d:\school_work\big-data-view\web\118 旅游智慧中心
3. 双击 index.html 文件

# 方法 B：右键打开
1. 右键点击 index.html
2. 选择"打开方式" → 选择浏览器（Chrome、Firefox、Edge 等）
```

### Mac
```bash
# 方法 A：直接双击
1. 打开 Finder
2. 导航到项目文件夹
3. 双击 index.html 文件

# 方法 B：右键打开
1. 右键点击 index.html
2. 选择"打开方式" → 选择浏览器
```

### Linux
```bash
# 方法 A：使用命令行
cd "/d/school_work/big-data-view/web/118 旅游智慧中心"
firefox index.html
# 或
google-chrome index.html

# 方法 B：直接双击
1. 打开文件管理器
2. 导航到项目文件夹
3. 双击 index.html 文件
```

---

## 方法 2：使用本地服务器（推荐用于开发）

### 使用 Python
```bash
# Python 3
cd "/d/school_work/big-data-view/web/118 旅游智慧中心"
python -m http.server 8000

# 然后在浏览器中打开：
# http://localhost:8000
```

### 使用 Node.js
```bash
# 安装 http-server
npm install -g http-server

# 启动服务器
cd "/d/school_work/big-data-view/web/118 旅游智慧中心"
http-server

# 然后在浏览器中打开显示的地址
```

### 使用 VS Code Live Server
```bash
# 1. 在 VS Code 中安装 Live Server 扩展
# 2. 右键点击 index.html
# 3. 选择 "Open with Live Server"
```

---

## 常见问题排查

### ❌ 问题：页面显示空白
**解决方案：**
1. 打开浏览器开发者工具（F12）
2. 查看 Console 标签是否有错误
3. 检查 Network 标签，看是否有文件加载失败
4. 确保所有 CSS 和 JS 文件都在 `static/` 文件夹中

### ❌ 问题：样式没有加载
**解决方案：**
1. 检查 `static/style.css` 文件是否存在
2. 检查浏览器控制台是否有 404 错误
3. 尝试清除浏览器缓存（Ctrl+Shift+Delete）
4. 使用本地服务器而不是直接打开文件

### ❌ 问题：图表没有显示
**解决方案：**
1. 检查 `static/echarts.min.js` 是否加载
2. 检查 `static/chart.js` 是否加载
3. 检查 `static/index.js` 是否加载
4. 打开浏览器控制台查看具体错误信息

### ❌ 问题：数据没有显示
**解决方案：**
1. 检查 `static/setData.js` 是否加载
2. 检查 `static/date.js` 是否加载
3. 检查 `static/weather.js` 是否加载
4. 打开浏览器控制台查看具体错误信息

---

## 推荐的浏览器

| 浏览器 | 版本 | 兼容性 |
|--------|------|--------|
| Chrome | 90+ | ✅ 完美 |
| Firefox | 88+ | ✅ 完美 |
| Safari | 14+ | ✅ 完美 |
| Edge | 90+ | ✅ 完美 |
| IE | 11 | ❌ 不支持 |

---

## 文件结构检查

确保以下文件都存在：

```
118 旅游智慧中心/
├── index.html                    ✅ 主页面
├── static/
│   ├── style.css                ✅ 样式表
│   ├── common.css               ✅ 通用样式
│   ├── chart.js                 ✅ 图表配置
│   ├── index.js                 ✅ 初始化脚本
│   ├── echarts.min.js           ✅ ECharts 库
│   ├── jquery.js                ✅ jQuery 库
│   ├── date.js                  ✅ 日期脚本
│   ├── weather.js               ✅ 天气脚本
│   ├── satisfaction.js          ✅ 满意度脚本
│   ├── setData.js               ✅ 数据脚本
│   ├── rem.js                   ✅ REM 脚本
│   ├── map.jpg                  ✅ 地图图片
│   ├── car.png                  ✅ 车图标
│   └── car1.png                 ✅ 车图标
└── ...其他文件
```

---

## 快速测试

打开页面后，你应该看到：

1. ✅ 顶部发光的标题栏
2. ✅ 左侧蓝色卡片面板
3. ✅ 中间的地图区域
4. ✅ 右侧蓝色卡片面板
5. ✅ 各种图表和数据显示

---

## 开发建议

### 修改样式
1. 编辑 `static/style.css`
2. 保存文件
3. 刷新浏览器（F5 或 Ctrl+R）

### 修改图表
1. 编辑 `static/chart.js`
2. 保存文件
3. 刷新浏览器

### 修改数据
1. 编辑 `static/setData.js`
2. 保存文件
3. 刷新浏览器

---

## 获取帮助

如果还是打不开，请：

1. 检查文件路径是否正确
2. 确保所有依赖文件都存在
3. 尝试使用不同的浏览器
4. 尝试使用本地服务器
5. 查看浏览器控制台的错误信息

---

**祝你使用愉快！** 🎉
