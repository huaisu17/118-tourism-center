var siz2;
var regionalElevation = {
	"\u5e7f\u5dde\u5e02": 35,
	"\u6df1\u5733\u5e02": 45,
	"\u73e0\u6d77\u5e02": 20,
	"\u6c55\u5934\u5e02": 25,
	"\u4f5b\u5c71\u5e02": 30,
	"\u97f6\u5173\u5e02": 420,
	"\u6cb3\u6e90\u5e02": 360,
	"\u6885\u5dde\u5e02": 320,
	"\u60e0\u5dde\u5e02": 140,
	"\u6c55\u5c3e\u5e02": 85,
	"\u4e1c\u839e\u5e02": 25,
	"\u4e2d\u5c71\u5e02": 15,
	"\u6c5f\u95e8\u5e02": 95,
	"\u9633\u6c5f\u5e02": 90,
	"\u6e5b\u6c5f\u5e02": 55,
	"\u8302\u540d\u5e02": 110,
	"\u8087\u5e86\u5e02": 210,
	"\u6e05\u8fdc\u5e02": 430,
	"\u6f6e\u5dde\u5e02": 120,
	"\u63ed\u9633\u5e02": 80,
	"\u4e91\u6d6e\u5e02": 260
};
var elevationColorStops = [
	{ value: 0, color: [34, 166, 179] },
	{ value: 80, color: [47, 196, 125] },
	{ value: 180, color: [147, 208, 70] },
	{ value: 300, color: [242, 198, 64] },
	{ value: 450, color: [235, 129, 52] }
];
var nationalElevationData = [
	{ name: "\u5317\u4eac", value: 45 },
	{ name: "\u5929\u6d25", value: 8 },
	{ name: "\u4e0a\u6d77", value: 4 },
	{ name: "\u91cd\u5e86", value: 400 },
	{ name: "\u6cb3\u5317", value: 120 },
	{ name: "\u6cb3\u5357", value: 110 },
	{ name: "\u4e91\u5357", value: 1980 },
	{ name: "\u8fbd\u5b81", value: 150 },
	{ name: "\u9ed1\u9f99\u6c5f", value: 220 },
	{ name: "\u6e56\u5357", value: 210 },
	{ name: "\u5b89\u5fbd", value: 120 },
	{ name: "\u5c71\u4e1c", value: 45 },
	{ name: "\u65b0\u7586", value: 1280 },
	{ name: "\u6c5f\u82cf", value: 18 },
	{ name: "\u6d59\u6c5f", value: 160 },
	{ name: "\u6c5f\u897f", value: 170 },
	{ name: "\u6e56\u5317", value: 160 },
	{ name: "\u5e7f\u897f", value: 260 },
	{ name: "\u7518\u8083", value: 1280 },
	{ name: "\u5c71\u897f", value: 1000 },
	{ name: "\u5185\u8499\u53e4", value: 1050 },
	{ name: "\u9655\u897f", value: 900 },
	{ name: "\u5409\u6797", value: 250 },
	{ name: "\u798f\u5efa", value: 230 },
	{ name: "\u8d35\u5dde", value: 1100 },
	{ name: "\u5e7f\u4e1c", value: 180 },
	{ name: "\u9752\u6d77", value: 3000 },
	{ name: "\u897f\u85cf", value: 4500 },
	{ name: "\u56db\u5ddd", value: 750 },
	{ name: "\u5b81\u590f", value: 1100 },
	{ name: "\u6d77\u5357", value: 180 },
	{ name: "\u53f0\u6e7e", value: 500 },
	{ name: "\u9999\u6e2f", value: 120 },
	{ name: "\u6fb3\u95e8", value: 48 }
];

var regionalLabelTweaks = {
	"广州市": { dx: 0, dy: 0, fontSize: 18 },
	"佛山市": { dx: 4, dy: 8, fontSize: 16 },
	"东莞市": { dx: 0, dy: -2, fontSize: 14 },
	"深圳市": { dx: 34, dy: 18, fontSize: 14 },
	"中山市": { dx: 10, dy: 0, fontSize: 14 },
	"珠海市": { dx: 0, dy: 0, fontSize: 14 },
	"江门市": { dx: 0, dy: -10, fontSize: 15 },
	"惠州市": { dx: 18, dy: 4, fontSize: 15 },
	"肇庆市": { dx: -6, dy: -10, fontSize: 16 },
	"汕头市": { dx: 5, dy: 15, fontSize: 14 },
	"潮州市": { dx: 0, dy: 8, fontSize: 13 },
	"揭阳市": { dx: -10, dy: 18, fontSize: 14 },
	"汕尾市": { dx: 0, dy: 0, fontSize: 14 }
};

function normalizeRegionName(regionName) {
	if (!regionName) {
		return "";
	}
	return regionName.replace(/\s+/g, "").replace(/[?]+$/g, "");
}

function rgbToHex(rgb) {
	var hex = "#";
	for (var i = 0; i < rgb.length; i++) {
		var value = Math.max(0, Math.min(255, Math.round(rgb[i])));
		var part = value.toString(16);
		hex += part.length === 1 ? "0" + part : part;
	}
	return hex;
}

function interpolateColor(startColor, endColor, ratio) {
	return [
		startColor[0] + (endColor[0] - startColor[0]) * ratio,
		startColor[1] + (endColor[1] - startColor[1]) * ratio,
		startColor[2] + (endColor[2] - startColor[2]) * ratio
	];
}

function getElevationColor(regionName) {
	var elevation = regionalElevation[regionName];
	if (typeof elevation !== "number") {
		elevation = 80;
	}

	for (var i = 0; i < elevationColorStops.length - 1; i++) {
		var currentStop = elevationColorStops[i];
		var nextStop = elevationColorStops[i + 1];
		if (elevation <= nextStop.value) {
			var ratio = (elevation - currentStop.value) / (nextStop.value - currentStop.value);
			return rgbToHex(interpolateColor(currentStop.color, nextStop.color, ratio));
		}
	}

	return rgbToHex(elevationColorStops[elevationColorStops.length - 1].color);
}

function sanitizeRegionalMapPaths(svgElement) {
	var paths = svgElement.querySelectorAll("path");
	for (var i = 0; i < paths.length; i++) {
		var regionName = normalizeRegionName(paths[i].getAttribute("name"));
		var pathData = paths[i].getAttribute("d") || "";
		if (regionName === "珠海市" && pathData.indexOf("M879,747.8") > -1) {
			paths[i].setAttribute("d", pathData.split("M879,747.8")[0].trim());
		}
	}
}

function applyRegionalMapColors(svgElement) {
	var paths = svgElement.querySelectorAll("path");
	for (var i = 0; i < paths.length; i++) {
		var regionName = normalizeRegionName(paths[i].getAttribute("name"));
		var fillColor = getElevationColor(regionName);
		paths[i].setAttribute("fill", fillColor);
		paths[i].setAttribute("data-base-fill", fillColor);
		paths[i].style.fill = fillColor;
		paths[i].style.stroke = "#FFFFFF";
		paths[i].style.strokeWidth = "2";
		paths[i].style.transition = "fill .2s ease, stroke .2s ease, stroke-width .2s ease";
	}
}

function appendRegionalMapLabels(svgElement) {
	var oldLabels = svgElement.querySelectorAll(".regional-map-label");
	for (var i = 0; i < oldLabels.length; i++) {
		oldLabels[i].remove();
	}

	var paths = svgElement.querySelectorAll("path");
	for (var j = 0; j < paths.length; j++) {
		var path = paths[j];
		var regionName = normalizeRegionName(path.getAttribute("name"));
		if (!regionName) {
			continue;
		}
		var box = path.getBBox();
		if (!box.width || !box.height) {
			continue;
		}

		var label = document.createElementNS("http://www.w3.org/2000/svg", "text");
		var tweak = regionalLabelTweaks[regionName] || {};
		var fontSize = tweak.fontSize || Math.max(10, Math.min(18, Math.min(box.width / 3.2, box.height / 2.3)));
		var x = box.x + box.width / 2 + (tweak.dx || 0);
		var y = box.y + box.height / 2 + (tweak.dy || 0);
		label.setAttribute("x", x);
		label.setAttribute("y", y);
		label.setAttribute("text-anchor", "middle");
		label.setAttribute("dominant-baseline", "middle");
		label.setAttribute("class", "regional-map-label");
		label.setAttribute("fill", "#ffffff");
		label.setAttribute("font-size", fontSize.toFixed(1));
		label.setAttribute("font-weight", "700");
		label.setAttribute("stroke", "rgba(7, 31, 77, 0.85)");
		label.setAttribute("stroke-width", "1.4");
		label.setAttribute("paint-order", "stroke fill");
		label.setAttribute("pointer-events", "none");
		label.textContent = regionName;
		svgElement.appendChild(label);
	}
}

/*数据初始化-开始*/
function bindRegionalMapHover() {
	var path = document.querySelectorAll("#map1 path");
	for (let i = 0; i < path.length; i++) {
		path[i].onmouseenter = function() {
			path[i].style.fill = "#6AE5E5";
			path[i].style.stroke = "#3246FB";
			path[i].style.strokeWidth = "2.5";
		}
		path[i].onmouseleave = function() {
			var baseFill = path[i].getAttribute("data-base-fill") || path[i].getAttribute("fill") || "";
			path[i].style.fill = baseFill;
			path[i].style.strokeWidth = "2";
			path[i].style.stroke = "#FFFFFF";
		}
	}
}

function renderRegionalMap(svgMarkup) {
	$("#map1").html(svgMarkup);
	var svgElement = document.querySelector("#map1 svg");
	if (!svgElement) {
		return;
	}
	sanitizeRegionalMapPaths(svgElement);
	applyRegionalMapColors(svgElement);
	appendRegionalMapLabels(svgElement);
	bindRegionalMapHover();
}

renderRegionalMap(beihai);
//中间板块切换（默认显示中国地图）
$(".bodyMiddle .navbar").find("span").each(function(index, item) {
	$(this).click(function() {
		$(".bodyMiddle .navbar").find("span").removeClass("active");
		$(".mapmain").find(".map").hide();
		$(this).addClass("active");
		$(".mapmain").find(".map").eq(index).fadeIn();
	})
})
//左边成交动态板块切换（默认显示猪板块内容）
for (var i = 0; i < CJstatus[1].length; i++) {
	$(".liushuihaoul .moveul").html((index, html) => {
		return html += `<li>
				<span>${CJstatus[1][i].num}</span>
				<span>${CJstatus[1][i].name}</span>
				<span>${CJstatus[1][i].cont}</span>
				<span>${CJstatus[1][i].weight}</span>
				<span>${CJstatus[1][i].time}</span>
				<span>${CJstatus[1][i].state}</span>
				</li>`
	})
}
siz2 = $(".liushuihaoul .moveul").find("li").length;
$(".liushuihaoul .moveul").css('height', $(".liushuihaoul .moveul").find("li").length * 50);
$(".liushuihaoul .moveul").html(function(index, value) {
	return value + value;
})
//左边成交动态切换板块时更新数据
$(".bodyRightTop .navbar").find("span").each(function(index, item) {
	$(this).click(function() {
		$(".bodyRightTop .navbar").find("span").removeClass("active");
		$(this).addClass("active");
		$(".liushuihaoul .moveul").html("")
		var Status = CJstatus[index];
		for (var i = 0; i < Status.length; i++) {
			$(".liushuihaoul .moveul").html((ind, html) => {
				return html += `<li>
				<span>${Status[i].num}</span>
				<span>${Status[i].name}</span>
				<span>${Status[i].cont}</span>
				<span>${Status[i].weight}</span>
				<span>${Status[i].time}</span>
				<span>${Status[i].state}</span>
				</li>`
			})
		}
		siz2 = $(".liushuihaoul .moveul").find("li").length;
		$(".liushuihaoul .moveul").css('height', $(".liushuihaoul .moveul").find("li").length * 50);
		$(".liushuihaoul .moveul").html(function(index, value) {
			return value + value;
		})
	})
})

//北海地图hove样式
//牧草产能区域分布
for (var i = 0; i < ChanNeng.length; i++) {
	$("#list").html(function(index, html) {
		return html += `<li><p>${ChanNeng[i].name}</p><span>${ChanNeng[i].num}</span></li>`
	})
};
//数据中心
for (var i = 0; i < DataCenter.length; i++) {
	$(".Data").html(function(index, html) {
		return html += `<li><span>${DataCenter[i].num}</span><p>${DataCenter[i].name}</p><i></i></li>`
	})
};
//占比小方块
for (var i = 0; i < 10; i++) {
	$(".GPZB").find("ul").html(function(index, html) {
		return html += `<li></li>`
	})
};
//六个竖直小方块组
for (var i = 0; i < 13; i++) {
	$(".fangkuai").html(function(index, html) {
		return html += `<li></li>`
	})
};
//时间刻度
for (var i = 0; i < 13; i++) {
	$(".kedu").find("ul").html(function(index, html) {
		return html += `<li></li>`
	})
};

var show = true;
/*数据初始化-开始*/
function rdm(min, max) {
	return parseInt(Math.random() * (max - min) + min);
}

function Show(obj, parent, scale, state) {
	if (show) {
		$(".mask").fadeIn(0);
		$("#animations").prependTo($(".mask"))
		$(obj).parent().appendTo($(".maskContent")).css("transform", "scale(" + scale + ")");
		$(obj).text("-").addClass("cancle")

		if (parent == 'footparent1') {
			console.log($("#jiage").find("canvas"))
			$("#jiage").find("canvas").css("z-index", "-1")
		}
		if (parent == 'footparent2') {
			$("#CJpie").find("canvas").css("z-index", "-1")
		}

	} else {
		if (state == "before") {
			$(obj).parent().prependTo($('.' + parent));
		} else if (state == "after") {
			$(obj).parent().appendTo($('.' + parent));
		}
		$(".mask").fadeOut(300)
		$("#animations").prependTo($(".content"));
		$(obj).parent().css("transform", "");
		$(obj).text("+").removeClass("cancle")

		if (parent == 'footparent1') {
			console.log($("#jiage").find("canvas"))
			$("#jiage").find("canvas").css("z-index", "0")
		}
		if (parent == 'footparent2') {
			$("#CJpie").find("canvas").css("z-index", "0")
		}
	}
	show = !show;
}

// ad()
guapai($(".guapai")[0]);
guapaizhanbi($(".left-top-right-circle")[0]);

//时间
(function() {
	let adata = new Date();
	let weekarr = ["日", "一", "二", "三", "四", "五", "六"];
	let time = adata.getHours() + ":" + Fill(adata.getMinutes()) + ":" + Fill(adata.getSeconds());
	let year = adata.getFullYear() + "年" + (adata.getMonth() + 1) + "月" + adata.getDate() + "日";
	let week = adata.getDay();

	function Fill(data) { //分钟秒钟空位补0
		if (data < 10) {
			return "0" + data;
		} else {
			return data;
		}
	}
	$("#time").text(time);
	$("#year").text(year);
	$("#week").text("星期" + weekarr[week]);
	setInterval(function() {
		adata = new Date();
		weekarr = ["日", "一", "二", "三", "四", "五", "六"];
		time = adata.getHours() + ":" + Fill(adata.getMinutes()) + ":" + Fill(adata.getSeconds());
		year = adata.getFullYear() + "年" + (adata.getMonth() + 1) + "月" + adata.getDate() + "日";
		week = adata.getDay();
		$("#time").text(time);
		$("#year").text(year);
		$("#week").text("星期" + weekarr[week]);
	}, 1000)
	//天气接口
	let wetherarr = ["多云转阵雨", "32~28℃", "优"];
	$("#sky").text(wetherarr[0])
	$("#temperatur").text(wetherarr[1])
	$("#state").text(wetherarr[2])
}());
//挂牌
function guapai(obj) {
	// 基于准备好的dom，初始化echarts实例
	var myChart = echarts.init(obj);
	// 指定图表的配置项和数据
	var option = {
		title: {
			text: ''
		},
		tooltip: {},
		legend: {
			data: ['排查总量', "已完成", "待整改"],
			bottom: 30,
			textStyle: { //字体样式
				color: "#fff",
				fontSize: 14,
				fontWeight: "lighter"
			},
			itemGap: 56, //图块间隙
			itemWidth: 44, //图块宽
			itemHeight: 18 //图块高
		},
		grid: {
			top: "15%",
			left: '1%', //折线框左边距
			right: '10%', //折线框右边距
			bottom: '1%', //折线框下边距
			height: '300px',
			containLabel: true
		},
		xAxis: {
			type: 'category',
			name: "时段",
			data: ["9:00-10:00", "10:00-11:00", "11:00-12:00", "14:00-15:00", "15:00-16:00", "16:00-17:00", "17:00-18:00"],
			nameTextStyle: { //坐标轴名称样式
				color: "#fff",
				fontSize: "14",
				backgroundColor: "" //文字块背景色
			},
			nameGap: 20, //坐标名称与轴线的距离
			axisTick: { //坐标刻度线样式
				lineStyle: {
					color: "#fff"
				}
			},
			axisLabel: { //坐标轴刻度标签名样式
				color: "#fff",
				fontSize: "12",
				rotate: 0, //文字倾斜(当刻度标签名过长时使用)
				interval: 0 //显示全部                
			},
			axisLine: { //坐标轴线设置
				show: true,
				lineStyle: {
					color: "#fff",
					width: "2"
				},
				symbol: ["none", "arrow"], //坐标轴末端箭头
				symbolSize: [8, 20], //箭头高度和宽度
				symbolOffset: [0, 16] //箭头与轴线端点的距离
			},
			splitNumber: 7,
		},
		yAxis: {
			name: "单位数",
			splitNumber: 8,
			nameTextStyle: { //坐标轴名称样式
				color: "#fff",
				fontSize: "14",
				backgroundColor: "" //文字块背景色
			},
			nameGap: 20, //坐标名称与轴线的距离
			axisLine: { //坐标轴线设置
				show: true,
				lineStyle: {
					color: "#fff",
					width: "2"
				},
				symbol: ["none", "arrow"], //坐标轴末端箭头
				symbolSize: [8, 20], //箭头高度和宽度
				symbolOffset: [0, 16] //箭头与轴线端点的距离
			},
			axisTick: { //坐标刻度线样式
				lineStyle: {
					color: "#fff"
				}
			},
			axisLabel: { //坐标轴刻度标签名样式
				color: "#fff",
				fontSize: "12"
			},
			splitLine: { //垂直分割线
				show: true,
				lineStyle: {
					color: "#02416D",
					width: "1"
				}
			},
		},
		series: [{
			name: '排查总量',
			type: 'bar',
			color: new echarts.graphic.LinearGradient(0, 0, 1, 0, [{
				offset: 0,
				color: '#3E3CB5'
			}, {
				offset: 1,
				color: '#D66BFD'
			}]),
			data: [21, 21, 21, 21, 21, 21, 21]
		}, {
			name: '已完成',
			type: 'bar',
			color: new echarts.graphic.LinearGradient(0, 0, 1, 0, [{
				offset: 0,
				color: '#0D52A1'
			}, {
				offset: 1,
				color: '#09F6FD'
			}]),
			data: [18, 19, 18, 20, 19, 18, 20]
		}, {
			name: '待整改',
			type: 'bar',
			color: new echarts.graphic.LinearGradient(0, 0, 1, 0, //颜色线性渐变:Linear，（径向渐变：Radial）
				[{
					offset: 0,
					color: '#025B71'
				}, {
					offset: 1,
					color: '#3BFE91'
				}]
			),
			//barGap:0,                                           //条形组中各条形图之间的距离
			//barCategoryGap:100,                                   //条形组之间的距离
			data: [3, 2, 3, 1, 2, 3, 1],
			//barWidth:30,
			// barMaxWidth:30
		}]
	};
	// 使用刚指定的配置项和数据显示图表。
	myChart.setOption(option);

	// setInterval(function() {
	// 	myChart.refresh();
	// }, 2000)
};
//挂牌占比
function guapaizhanbi(obj, Index) {
	var echartdata = [30.2, 52.3, 8.9, 8.6];
	var rich = {
		yellow: {
			color: "#ffc72b",
			fontSize: 18,
			padding: [2, 4],
			align: 'center'
		},
		total: {
			color: "#ffc72b",
			fontSize: 20,
			align: 'center'
		},
		white: {
			color: "#fff",
			align: 'center',
			fontSize: 16,
			padding: [10, 0]
		},
		blue: {
			color: '#49dff0',
			fontSize: 16,
			align: 'center'
		},
		hr: {
			borderColor: 'auto',
			width: '100%',
			borderWidth: 1,
			height: 0,
		}
	};
	var myChart = echarts.init(obj);
	var option = {
		tooltip: {
			trigger: 'item',
			formatter: "{b}: {c} ({d}%)"
		},
		series: [{
			type: 'pie',
			label: {
				fontSize: 24,
				normal: {
					color: "#fff",
					//formatter: '{b|{b}\n     {d}%}',
					formatter: function(params, ticket, callback) {
						var total = 0; //总数量
						var percent = 0; //占比
						echartdata.forEach(function(value, index) {
							total += value;
						});
						percent = ((params.value / total) * 100).toFixed(1);
						return '{white|' + params.name + '}\n\n{yellow|' + params.value + '}\n{blue|' + percent + '%}';
					},
					// borderWidth: 0,
					// borderRadius: 4,
					// shadowBlur:3,
					// shadowOffsetX: 2,
					// shadowOffsetY: 2,
					// shadowColor: '#999',
					padding: [0, -50],
					rich: rich
				}
			},
			labelLine: {
				lineStyle: {
					//color: auto
					width: 2
				},
				length: 20,
				length2: 50
			},
			radius: ['40%', '60%'],
			data: [{
				value: echartdata[0],
				itemStyle: {
					color: "#1D3EF9"
				},
				name: '蔬菜成本'
			}, {
				value: echartdata[1],
				itemStyle: {
					color: "#FBED14"
				},
				name: '肉类成本'
			}, {
				value: echartdata[2],
				itemStyle: {
					color: "#3BF88F",
				},
				name: '主食成本'
			}, {
				value: echartdata[3],
				itemStyle: {
					color: "#46F0FF",
				},
				name: '副食成本'
			}]
		}, ]
	};
	if (option.visualMap) {
		option.visualMap.text = ['4500m', '0m'];
		option.series[0].data = nationalElevationData;
		option.tooltip.formatter = function(params) {
		return params.name + '<br/>平均海拔：' + params.value + 'm';
		};
	}
	myChart.setOption(option);
	var Oitem = $(".bodyLeftTopGPZB");
	var total = 650;
	var n = 0;
	run();

	function run() {
		for (var i = 0; i < echartdata.length; i++) {
			n = echartdata[i] / 100; //每个格子数值100,10个格子1000
			Oitem.each((index, item) => {
				$(item).find(".GPZB").eq(i).find("span").text(echartdata[i]);
				$(item).find(".GPZB").eq(i).find("li").each(function(ind, it) {
					if (ind <= n) {
						$(it).css("background", "#00A0E9")
					} else {
						$(it).css("background", "#1D2088")
					}
				})
			})
		}
	}
	//方块格子动画高亮特效
	Oitem.each((index, item) => {
		$(item).find(".GPZB").each(function(ind, it) {
			var _this = $(this);
			let t = 0;
			setInterval(function() {
				n = echartdata[ind] / 100; //每个格子数值100,10个格子1000
				$(it).find("li").each(function(inde, ite) {
					if (inde <= n) {
						$(ite).css("background", "#00A0E9");
						$(it).find("li").eq(t).css("background", "#FBED14");
					} else {
						$(ite).css("background", "#1D2088");
					}
				})
				t++;
				if (t > n + 1) t = 0;
			}, 300)
		})
	})
}
//中国地图
(function() {
	var myChart = echarts.init($("#map")[0]);
	var option = {
		tooltip: { //鼠标hover是提示信息
			show: true, //不显示提示标签
			formatter: '{b}', //提示标签格式
			backgroundColor: "#ff7f50", //提示标签背景颜色
			textStyle: {
				color: "#fff",
				fontSize: "20"
			} //提示标签字体颜色
		},
		visualMap: { //视觉映射组件()
			type: "continuous", //连续型
			min: 0,
			max: 4500,
			left: 990,
			top: 800,
			text: ['150', '0'], // 文本，默认为数值文本
			textGap: 10, //文本与图形之间的距离
			itemWidth: 40, //图形的宽
			itemHeight: 200, //突刺是哪个的高
			calculable: true, //是否显示拖动手柄
			textStyle: {
				color: "#fff",
				fontSize: 25,


			}, //省份标签字体颜色
			//align:"left",
			//inverse: true, //反向
			inRange: { //地图颜色变化
				color: ['#22A6B3', '#2FC47D', '#93D046', '#F2C640', '#EB8134']
			}
			// outOfRange:{
			// 	symbolSize: [100, 100]
			// }
		},
		series: [{
			type: 'map',
			color: "red",
			mapType: 'china',
			roam: "false", //是否开启缩放平移
			label: { //标签字体样式
				position: "inside",
				normal: { //正常情况下显示效果
					show: true, //显示省份标签
					textStyle: {
						color: "#fff",
						fontSize: 20
					} //省份标签字体颜色
				},
				emphasis: { //对应的鼠标悬浮效果
					show: true,
					textStyle: {
						color: "#800080"
					}
				}
			},
			itemStyle: {
				normal: {
					borderWidth: 2, //区域边框宽度
					borderColor: '#fff', //区域边框颜色
					//areaColor: "#ffefd5", //区域颜色
					fontSize: "30"
				},
				emphasis: {
					borderWidth: 2,
					borderColor: '#3246FB',
					//areaColor: "red",
				},
			},
			data: [{
				name: '北京',
				value: 2500,
			}, {
				name: '天津',
				value: Math.round(Math.random() * 100)
			}, {
				name: '上海',
				value: Math.round(Math.random() * 100)
			}, {
				name: '重庆',
				value: Math.round(Math.random() * 100)
			}, {
				name: '河北',
				value: Math.round(Math.random() * 100)
			}, {
				name: '河南',
				value: Math.round(Math.random() * 100)
			}, {
				name: '云南',
				value: Math.round(Math.random() * 100)
			}, {
				name: '辽宁',
				value: Math.round(Math.random() * 100)
			}, {
				name: '黑龙江',
				value: Math.round(Math.random() * 100)
			}, {
				name: '湖南',
				value: Math.round(Math.random() * 100)
			}, {
				name: '安徽',
				value: Math.round(Math.random() * 100)
			}, {
				name: '山东',
				value: Math.round(Math.random() * 100)
			}, {
				name: '新疆',
				value: Math.round(Math.random() * 100)
			}, {
				name: '江苏',
				value: Math.round(Math.random() * 100)
			}, {
				name: '浙江',
				value: Math.round(Math.random() * 100)
			}, {
				name: '江西',
				value: Math.round(Math.random() * 100)
			}, {
				name: '湖北',
				value: Math.round(Math.random() * 100)
			}, {
				name: '广西',
				value: Math.round(Math.random() * 100)
			}, {
				name: '甘肃',
				value: Math.round(Math.random() * 100)
			}, {
				name: '山西',
				value: Math.round(Math.random() * 100)
			}, {
				name: '内蒙古',
				value: Math.round(Math.random() * 100)
			}, {
				name: '陕西',
				value: Math.round(Math.random() * 100)
			}, {
				name: '吉林',
				value: Math.round(Math.random() * 100)
			}, {
				name: '福建',
				value: Math.round(Math.random() * 100)
			}, {
				name: '贵州',
				value: Math.round(Math.random() * 100)
			}, {
				name: '广东',
				value: Math.round(Math.random() * 100)
			}, {
				name: '青海',
				value: Math.round(Math.random() * 100)
			}, {
				name: '西藏',
				value: Math.round(Math.random() * 100)
			}, {
				name: '四川',
				value: Math.round(Math.random() * 100)
			}, {
				name: '宁夏',
				value: Math.round(Math.random() * 100)
			}, {
				name: '海南',
				value: Math.round(Math.random() * 100)
			}, {
				name: '台湾',
				value: Math.round(Math.random() * 100)
			}, {
				name: '香港',
				value: Math.round(Math.random() * 100)
			}, {
				name: '澳门',
				value: Math.round(Math.random() * 100)
			}]
		}],
	};
	option.visualMap.text = ['4500m', '0m'];
	option.series[0].data = nationalElevationData;
	option.tooltip.formatter = function(params) {
		return params.name + '<br/>平均海拔：' + params.value + 'm';
	};
	myChart.setOption(option);
	myChart.on('mouseover', function(params) {
		var dataIndex = params.dataIndex;
		console.log(params);
	});
}());
//大盘走势
(function() {
	// 基于准备好的dom，初始化echarts实例
	var myChart = echarts.init($("#map2")[0]);
	// 指定图表的配置项和数据
	var option = {
		textStyle: { //全局字体样式设置
			color: "#000",
			fontSize: 30,
			fontWeight: "lighter"
		},
		nameTextStyle: { //轴名称字体样式
			color: "#0BA4E8",
			fontWeight: "normal"
		},
		tooltip: { //鼠标hover显示提示信息
			trigger: 'axis'
		},
		legend: {
			data: ['01县', '02县', '03县', "04县"],
			//width: 40,
			//height: 40,
			//right: 50,
			top: 130,
			//orient: "vertical", //纵向排列
			itemGap: 25,
			textStyle: {
				color: "#fff",
				fontSize: "24"
			}
		},
		grid: {
			top: "20%",
			left: '1%', //折线框左边距
			right: '11%', //折线框右边距
			bottom: '8%', //折线框下边距
			containLabel: true
		},
		color: ['#46B05D', '#AF4B87', '#03A2E9', "#F5D52E"],
		xAxis: {
			type: 'category',
			name: "月份",
			nameGap: 24,
			nameTextStyle: { //坐标轴名称样式
				color: "#fff",
				fontSize: "24",
				backgroundColor: "" //文字块背景色
			},
			data: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
			splitLine: { //垂直分割线
				show: false,
				lineStyle: {
					color: "#ccc",
					width: "0.5"
				}
			},
			splitArea: {
				show: ''
			},
			axisLine: { //坐标轴线设置
				show: true,
				lineStyle: {
					color: "#fff",
					width: "2"
				},
				symbol: ["none", "arrow"], //坐标轴末端箭头
				symbolSize: [8, 20], //箭头高度和宽度
				symbolOffset: [0, 16] //箭头与轴线端点的距离
			},
			axisTick: { //坐标刻度线样式
				lineStyle: {
					color: "#fff",
					width: "2"
				}
			},
			axisLabel: { //刻度线
				textStyle: {
					color: "#fff",
					fontSize: 24,
					fontWeight: "normal",
					interval: 0 //显示全部  					
				}
			},
			// axisLabel: { //坐标轴刻度标签名样式
			// 	color: "#fff",
			// 	fontSize: "14",
			// 	rotate: 0, //文字倾斜(当刻度标签名过长时使用)
			// 	interval: 0 //显示全部                
			// },
		},
		yAxis: {
			type: 'value',
			//splitNumber: 5 ,				//轴分割段数
			min: 0, //轴坐标最小值
			//max:1300,						//轴坐标最大值
			interval: 260, //强制每段260分割
			boundaryGap: false,
			name: "金额",
			nameGap: 30,
			nameTextStyle: { //坐标轴名称样式
				color: "#fff",
				fontSize: "24",
				//backgroundColor: "" //文字块背景色

			},

			axisLine: { //坐标轴线设置
				show: true,
				lineStyle: {
					color: "#fff",
					width: "2"
				},
				symbol: ["none", "arrow"], //坐标轴末端箭头
				symbolSize: [8, 20], //箭头高度和宽度
				symbolOffset: [0, 16] //箭头与轴线端点的距离			
			},
			splitLine: { //水平垂直分割线样式
				show: true,
				lineStyle: {
					color: "#02416D",
					width: "0.5"
				}
			},
			axisTick: { //坐标刻度线样式
				lineStyle: {
					color: "#fff",
					width: "2"
				}
			},
			axisLabel: { //轴刻度字体样式设置
				textStyle: {
					color: "#fff",
					fontSize: 24,
					fontWeight: "normal",
					interval: 0 //显示全部  
				}
			}
		},
		series: [{
			name: '01县',
			type: 'line',
			//symbol:"circle", 				//标记图像样式（折线上的点的样式，默认为圆形）
			symbolSize: "6",
			itemStyle: { //折线观点的样式
				//color: "red",
				//borderColor: "green"
			},
			areaStyle: { //区域填充样式
				color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
					offset: 0,
					color: 'rgba(62,155,93,.1)'
				}, {
					offset: 1,
					color: 'rgba(62,155,93,.3)'
				}]),
			},
			smooth: false, //折线是否平滑
			data: [120, 180, 260, 320, 410, 520, 610, 670, 620, 560, 660, 760]
		}, {
			name: '02县',
			type: 'line',
			smooth: false,
			areaStyle: { //区域填充样式
				color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
					offset: 0,
					color: 'rgba(173,75,135,.1)'
				}, {
					offset: 1,
					color: 'rgba(173,75,135,.3)'
				}]),
			},
			data: [100, 150, 220, 300, 380, 460, 520, 610, 580, 530, 610, 690]
		}, {
			name: '03县',
			type: 'line',
			smooth: false,
			areaStyle: { //区域填充样式
				color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
					offset: 0,
					color: 'rgba(3,160,230,.1)'
				}, {
					offset: 1,
					color: 'rgba(3,160,230,.3)'
				}]),
			},
			data: [80, 130, 190, 260, 340, 420, 500, 560, 540, 500, 560, 640]
		}, {
			name: '04县',
			type: 'line',
			smooth: false,
			areaStyle: { //区域填充样式
				color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
					offset: 0,
					color: 'rgba(230,206,52,.1)'
				}, {
					offset: 1,
					color: 'rgba(230,206,52,.3)'
				}]),
			},
			data: [90, 140, 210, 280, 360, 440, 520, 590, 560, 520, 600, 680]
		}]
	};
	// 使用刚指定的配置项和数据显示图表。
	myChart.setOption(option);
}());

//价格走势
(function() {
	// 基于准备好的dom，初始化echarts实例
	var chartDom = $("#jiagezoushi")[0];
	if (!chartDom) {
		return;
	}
	var myChart = echarts.init(chartDom);
	// 指定图表的配置项和数据
	var option = {
		textStyle: { //全局字体样式设置
			color: "#000",
			fontSize: 30,
			fontWeight: "lighter"
		},
		nameTextStyle: { //轴名称字体样式
			color: "#0BA4E8",
			fontWeight: "normal"
		},
		tooltip: { //鼠标hover显示提示信息
			trigger: 'axis'
		},
		legend: {
			data: ['一年生暖季牧草', '一年生冷季牧草', '多年生牧草', "进口饲草"],
			right: 100,
			top: 40,
			textStyle: {
				color: "#fff",
				fontSize: "22"
			}
		},
		grid: {
			top: "25%",
			left: '1%', //折线框左边距
			right: '9%', //折线框右边距
			bottom: '6%', //折线框下边距
			containLabel: true
		},
		color: ['#46B05D', '#AF4B87', '#03A2E9'],
		xAxis: {
			type: 'category',
			name: "2018年",
			nameTextStyle: { //坐标轴名称样式
				color: "#fff",
				fontSize: "24",
				backgroundColor: "" //文字块背景色
			},
			data: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月', ],
			splitLine: { //垂直分割线
				show: true,
				lineStyle: {
					color: "#02416D",
					width: "0.5"
				}
			},
			splitArea: {
				show: ''
			},
			axisLine: { //坐标轴线设置
				show: true,
				lineStyle: {
					color: "#fff",
					width: "2"
				},
				symbol: ["none", "arrow"], //坐标轴末端箭头
				symbolSize: [8, 20], //箭头高度和宽度
				symbolOffset: [0, 16] //箭头与轴线端点的距离
			},
			axisTick: { //坐标刻度线样式
				lineStyle: {
					color: "#fff",
					width: "2"
				}
			},
			axisLabel: { //刻度线
				textStyle: {
					color: "#fff",
					fontSize: 30,
					fontWeight: "normal"
				}
			},
		},
		yAxis: {
			type: 'value',
			//splitNumber: 5 ,				//轴分割段数
			min: 0, //轴坐标最小值
			//max:1300,						//轴坐标最大值
			interval: 260, //强制每段260分割
			boundaryGap: false,
			name: "kg/元",
			nameTextStyle: { //坐标轴名称样式
				color: "#fff",
				fontSize: "24",
			},
			axisLine: { //坐标轴线设置
				show: true,
				lineStyle: {
					color: "#fff",
					width: "2"
				},
				symbol: ["none", "arrow"], //坐标轴末端箭头
				symbolSize: [8, 20], //箭头高度和宽度
				symbolOffset: [0, 16] //箭头与轴线端点的距离			
			},
			splitLine: { //垂直分割线
				show: true,
				lineStyle: {
					color: "#02416D",
					width: "0.5"
				}
			},
			splitLine: { //垂直分割线
				show: true,
				lineStyle: {
					color: "#02416D",
					width: "0.5"
				}
			},
			axisTick: { //坐标刻度线样式
				lineStyle: {
					color: "#fff",
					width: "2"
				}
			},
			axisLabel: { //轴刻度字体样式设置
				textStyle: {
					color: "#fff",
					fontSize: 20,
					fontWeight: "normal"
				}
			}
		},
		series: [{
			name: '一年生暖季牧草',
			type: 'line',
			//symbol:"circle", 				//标记图像样式（折线上的点的样式，默认为圆形）
			symbolSize: "6",
			itemStyle: { //折线观点的样式
				//color:"#111947",
				//borderColor:"green"
			},
			smooth: true, //折线是否平滑
			data: [1300, 1300, 1300, 1300, 1190, 800, 900, 1000, 1200, 1000, 900, 850]
		}, {
			name: '一年生冷季牧草',
			type: 'line',
			smooth: true,
			data: [1220, 1220, 1220, 1220, 1100, 720, 820, 920, 1000, 1200, 1500, 1300]
		}, {
			name: '多年生牧草',
			type: 'line',
			smooth: true,
			data: [1000, 900, 800, 620, 680, 680, 720, 840, 1230, 1000, 900, 1100, ]
		}, {
			name: '进口饲草',
			type: 'line',
			smooth: true,
			data: [1200, 970, 1000, 720, 780, 880, 920, 740, 900, 1000, 800, 1200]
		}]
	};
	// 使用刚指定的配置项和数据显示图表。
	myChart.setOption(option);
}());
// 当前成交总数和月成交总数
(function() {
	var echartdata = [18.9, 20.3, 10.7, 32.8];
	var rich = {
		yellow: {
			color: "#ffc72b",
			fontSize: 18,
			padding: [2, 4],
			align: 'center'
		},
		total: {
			color: "#ffc72b",
			fontSize: 20,
			align: 'center'
		},
		white: {
			color: "#fff",
			align: 'center',
			fontSize: 16,
			padding: [10, 0]
		},
		blue: {
			color: '#49dff0',
			fontSize: 16,
			align: 'center'
		},
		hr: {
			borderColor: 'auto',
			width: '100%',
			borderWidth: 1,
			height: 0,
		}
	};
	if (!$("#right-top-compare").length) {
		$(".rightTopComparePanel").append('<div id="right-top-compare"></div>');
	}
	$(".rightTopCompareLegend").remove();
	$(".rightTopCompareDetail").remove();
	var myChart = echarts.init($("#left-bottom")[0]);
	var rightTopChart = echarts.init($("#right-top-bottom")[0]);
	var rightTopCompareChart = echarts.init($("#right-top-compare")[0]);

	var option = {
		tooltip: {
			trigger: 'item',
			formatter: "{b}: {c} ({d}%)"
		},
		legend: {
			data: ['待支付采购金额', "今日消费总额", "今日充值金额", "已支付采购金额"],

			bottom: 100,
			left: "right",
			right: 0,
			align: "left",
			textStyle: { //字体样式
				color: "#fff",
				fontSize: 14,
				fontWeight: "lighter"
			},
			itemGap: 56, //图块间隙
			itemWidth: 44, //图块宽
			itemHeight: 18, //图块高
			orient: "vertical"
		},
		grid: {
			top: "10%",
			// left: '1%', //折线框左边距
			right: '1%', //折线框右边距
			// bottom: '20%', //折线框下边距
			containLabel: true
		},
		series: [{
			type: 'pie',
			center: ['50%', '46%'],
			label: {
				fontSize: 24,
				normal: {
					color: "#fff",
					//formatter: '{b|{b}\n     {d}%}',
					formatter: function(params, ticket, callback) {
						var total = 0; //总数量
						var percent = 0; //占比
						echartdata.forEach(function(value, index) {
							total += value;
						});
						percent = ((params.value / total) * 100).toFixed(1);
						return '{white|' + params.name + '}\n\n{yellow|' + params.value + '}\n{blue|' + percent + '%}';
					},
					padding: [0, -50],
					rich: rich
				}
			},
			labelLine: {
				lineStyle: {
					//color: auto
					width: 2
				},
				length: 18,
				length2: 36
			},
			radius: ['28%', '45%'],
			data: [{
				value: echartdata[0],
				itemStyle: {
					color: "#E6C146"
				},
				name: '待支付采购金额'
			}, {
				value: echartdata[1],
				itemStyle: {
					color: "#46F0FF"
				},
				name: '今日消费总额'
			}, {
				value: echartdata[2],
				itemStyle: {
					color: "#D591FE",
				},
				name: '今日充值金额'
			}, {
				value: echartdata[3],
				itemStyle: {
					color: "#7689FF",
				},
				name: '已支付采购金额'
			}]
		}, ]
	};
	myChart.setOption(option);
	var scenicSpotMonths = ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00'];
	var scenicSpotTrend = [95, 410, 720, 670, 610, 560, 660, 772.99];
	var rightTopOption = {
		grid: {
			top: 72,
			left: 86,
			right: 12,
			bottom: 32
		},
		tooltip: {
			trigger: 'axis'
		},
		xAxis: {
			type: 'category',
			data: scenicSpotMonths,
			axisLine: {
				lineStyle: {
					color: 'rgba(129, 224, 255, .45)'
				}
			},
			axisLabel: {
				color: '#BEEBFF',
				fontSize: 18,
				interval: 0
			},
			axisTick: {
				show: false
			}
		},
		yAxis: {
			type: 'value',
			name: '消费值',
			nameTextStyle: {
				color: '#7fd3ff',
				padding: [0, 0, 0, -10]
			},
			splitLine: {
				lineStyle: {
					color: 'rgba(71, 146, 255, .15)'
				}
			},
			axisLine: {
				show: false
			},
			axisTick: {
				show: false
			},
			axisLabel: {
				color: '#8fd7ff'
			}
		},
		series: [{
			name: '消费数据',
			type: 'line',
			smooth: false,
			symbol: 'circle',
			symbolSize: 10,
			data: scenicSpotTrend,
			lineStyle: {
				color: '#35D8FF',
				width: 4
			},
			itemStyle: {
				color: '#F7C35F',
				borderColor: '#ffffff',
				borderWidth: 2
			},
			areaStyle: {
				color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
					offset: 0,
					color: 'rgba(53, 216, 255, .45)'
				}, {
					offset: 1,
					color: 'rgba(53, 216, 255, .05)'
				}])
			}
		}]
	};
	rightTopChart.setOption(rightTopOption);
	var nutritionCountyNames = ['01县', '02县', '03县', '04县'];
	var nutritionViceFood = [24, 37, 24, 37];
	var nutritionFreshFood = [42, 26, 42, 26];
	var nutritionGrainOil = [36, 24, 36, 24];
	var rightTopCompareOption = {
		tooltip: {
			trigger: 'axis',
			axisPointer: {
				type: 'shadow',
				shadowStyle: {
					color: 'rgba(88, 184, 255, .08)'
				}
			},
			formatter: function(params) {
				var total = 0;
				var html = params[0].name;
				params.forEach(function(item) {
					total += item.value;
					html += '<br/>' + item.seriesName + '：' + item.value;
				});
				return html + '<br/>总计：' + total;
			}
		},
		grid: {
			top: 6,
			left: 90,
			right: 24,
			bottom: 12
		},
		xAxis: {
			type: 'value',
			max: 120,
			splitNumber: 4,
			axisLabel: {
				color: '#7fcfff'
			},
			axisLine: {
				show: false
			},
			axisTick: {
				show: false
			},
			splitLine: {
				lineStyle: {
					color: 'rgba(88, 184, 255, .12)'
				}
			}
		},
		yAxis: {
			type: 'category',
			inverse: true,
			data: nutritionCountyNames,
			axisLine: {
				show: false
			},
			axisTick: {
				show: false
			},
			axisLabel: {
				color: '#d9f4ff',
				fontSize: 18
			}
		},
		series: [{
			name: '副食',
			type: 'bar',
			stack: 'nutrition',
			data: nutritionViceFood,
			barWidth: 18,
			itemStyle: {
				color: '#24d0be',
				borderRadius: [20, 0, 0, 20]
			},
			label: {
				show: true,
				position: 'inside',
				color: '#ffffff',
				formatter: '{c}'
			}
		}, {
			name: '生鲜',
			type: 'bar',
			stack: 'nutrition',
			data: nutritionFreshFood,
			barWidth: 18,
			itemStyle: {
				color: '#d89d38'
			},
			label: {
				show: true,
				position: 'inside',
				color: '#ffffff',
				formatter: '{c}'
			},
			z: 2
		}, {
			name: '粮油',
			type: 'bar',
			stack: 'nutrition',
			data: nutritionGrainOil,
			barWidth: 18,
			itemStyle: {
				color: '#4ba6f8',
				borderRadius: [0, 20, 20, 0]
			},
			label: {
				show: true,
				position: 'inside',
				color: '#ffffff',
				formatter: '{c}'
			},
			z: 3
		}]
	};
	rightTopCompareChart.setOption(rightTopCompareOption);
	var orderAnalysisData = [{
		name: '01县',
		value: 3410,
		example: '示例：01县午餐预约量稳定，学校食堂提前备餐。'
	}, {
		name: '02县',
		value: 3020,
		example: '示例：02县早餐集中下单，窗口分流效果较好。'
	}, {
		name: '03县',
		value: 3530,
		example: '示例：03县晚餐订单峰值明显，需重点保障主食供应。'
	}, {
		name: '04县',
		value: 3400,
		example: '示例：04县周末订单回落，配送批次可以适当压缩。'
	}];
	var orderChart = echarts.init($("#cp")[0]);
	var orderOption = {
		grid: {
			top: 36,
			left: 96,
			right: 34,
			bottom: 24
		},
		tooltip: {
			trigger: 'axis',
			axisPointer: {
				type: 'shadow',
				shadowStyle: {
					color: 'rgba(88, 184, 255, .12)'
				}
			},
			backgroundColor: 'rgba(4,18,52,.92)',
			borderColor: 'rgba(101,196,255,.26)',
			borderWidth: 1,
			textStyle: {
				color: '#dff6ff',
				fontSize: 14
			},
			formatter: function(params) {
				var point = params[0];
				var current = orderAnalysisData[point.dataIndex];
				return current.name + '<br/>订餐量：' + current.value + '<br/>' + current.example;
			}
		},
		xAxis: {
			type: 'value',
			min: 0,
			max: 4000,
			interval: 1000,
			axisLine: {
				lineStyle: {
					color: 'rgba(129, 224, 255, .45)'
				}
			},
			axisTick: {
				show: false
			},
			axisLabel: {
				color: '#BEEBFF',
				fontSize: 16
			},
			splitLine: {
				lineStyle: {
					color: 'rgba(88, 184, 255, .12)'
				}
			}
		},
		yAxis: {
			type: 'category',
			data: orderAnalysisData.map(function(item) {
				return item.name;
			}),
			inverse: true,
			axisTick: {
				show: false
			},
			axisLine: {
				show: true,
				lineStyle: {
					color: '#293CF8',
					width: 2
				}
			},
			axisLabel: {
				color: '#d9f4ff',
				fontSize: 18,
				margin: 12
			}
		},
		series: [{
			type: 'bar',
			data: orderAnalysisData.map(function(item) {
				return item.value;
			}),
			barWidth: 22,
			showBackground: true,
			backgroundStyle: {
				color: 'rgba(9,34,92,.18)',
				borderColor: '#1A6BD5',
				borderWidth: 1
			},
			label: {
				show: true,
				position: 'right',
				color: '#dff8ff',
				fontSize: 16
			},
			itemStyle: {
				borderRadius: [0, 14, 14, 0],
				color: new echarts.graphic.LinearGradient(0, 0, 1, 0, [{
					offset: 0,
					color: 'rgba(28,40,200,1)'
				}, {
					offset: 1,
					color: 'rgba(15,182,252,1)'
				}])
			},
			emphasis: {
				itemStyle: {
					shadowBlur: 18,
					shadowColor: 'rgba(15,182,252,.38)',
					color: new echarts.graphic.LinearGradient(0, 0, 1, 0, [{
						offset: 0,
						color: '#3557ff'
					}, {
						offset: 1,
						color: '#36d8ff'
					}])
				}
			}
		}]
	};
	orderChart.setOption(orderOption);
}());

// 数据变化趋势左侧环状图
(function() {
	var chartDom = $("#right-bottom-ring-chart")[0];
	if (!chartDom) {
		return;
	}
	var myChart = echarts.init(chartDom);
	var option = {
		tooltip: {
			trigger: 'item',
			formatter: "{b} : {c} ({d}%)"
		},
		legend: {
			top: '14%',
			left: 'center',
			data: ['A供', 'B供', 'C供', 'D供', 'E供'],
			icon: 'circle',
			textStyle: {
				color: 'rgba(255,255,255,.6)',
				fontSize: 18
			},
			itemGap: 18
		},
		calculable: true,
		series: [{
			name: '',
			color: ['#62c98d', '#2f89cf', '#4cb9cf', '#53b666', '#c9c862', '#c98b62', '#c962b9', '#c96262'],
			type: 'pie',
			startAngle: 0,
			radius: [108, 196],
			center: ['50%', '43%'],
			roseType: 'area',
			avoidLabelOverlap: false,
			label: {
				normal: {
					show: true,
					color: '#fff',
					fontSize: 16
				},
				emphasis: {
					show: true
				}
			},
			labelLine: {
				normal: {
					show: true,
					length2: 1
				},
				emphasis: {
					show: true
				}
			},
			data: [
				{ value: 96, name: 'A供' },
				{ value: 92, name: 'B供' },
				{ value: 88, name: 'C供' },
				{ value: 84, name: 'D供' },
				{ value: 79, name: 'E供' },
				{ value: 0, name: "", label: { show: false }, labelLine: { show: false } },
				{ value: 0, name: "", label: { show: false }, labelLine: { show: false } },
				{ value: 0, name: "", label: { show: false }, labelLine: { show: false } },
				{ value: 0, name: "", label: { show: false }, labelLine: { show: false } },
				{ value: 0, name: "", label: { show: false }, labelLine: { show: false } }
			]
		}]
	};
	myChart.setOption(option);
	window.addEventListener("resize", function() {
		myChart.resize();
	});
}());
//入驻动态滚动
(function() {
	var monthlyDispatchStatus = [
		"一月月调度情况",
		"二月月调度情况",
		"三月月调度情况",
		"四月月调度情况",
		"五月月调度情况",
		"六月月调度情况",
		"七月月调度情况",
		"八月月调度情况",
		"九月月调度情况",
		"十月月调度情况",
		"十一月月调度情况",
		"十二月月调度情况"
	];
	$(".bodyRightBottom .moveul").html("");
	for (var i = 0; i < monthlyDispatchStatus.length; i++) {
		$(".bodyRightBottom .moveul").html((index, html) => {
			return html += `<li><i></i><span>${monthlyDispatchStatus[i]}</span></li>`
		})
	}
	//获取实时数据后循环创建流水号滚动列表
	monthlyDispatchStatus = [{
		name: "一月月调度情况",
		url: "month-detail.html?month=1"
	}, {
		name: "二月月调度情况",
		url: "month-detail.html?month=2"
	}, {
		name: "三月月调度情况",
		url: "month-detail.html?month=3"
	}, {
		name: "四月月调度情况",
		url: "month-detail.html?month=4"
	}, {
		name: "五月月调度情况",
		url: "month-detail.html?month=5"
	}, {
		name: "六月月调度情况",
		url: "month-detail.html?month=6"
	}, {
		name: "七月月调度情况",
		url: "month-detail.html?month=7"
	}, {
		name: "八月月调度情况",
		url: "month-detail.html?month=8"
	}, {
		name: "九月月调度情况",
		url: "month-detail.html?month=9"
	}, {
		name: "十月月调度情况",
		url: "month-detail.html?month=10"
	}, {
		name: "十一月月调度情况",
		url: "month-detail.html?month=11"
	}, {
		name: "十二月月调度情况",
		url: "month-detail.html?month=12"
	}];
	$(".bodyRightBottom .moveul").html("");
	for (var j = 0; j < monthlyDispatchStatus.length; j++) {
		$(".bodyRightBottom .moveul").html((index, html) => {
			return html += `<li data-url="${monthlyDispatchStatus[j].url}"><i></i><span>${monthlyDispatchStatus[j].name}</span></li>`
		})
	}
	$(".bodyRightBottom .moveul").off("click.monthlyDispatch").on("click.monthlyDispatch", "li", function() {
		var url = $(this).data("url");
		if (url) {
			window.location.href = url;
		}
	});
	var rowHeight = $(".bodyRightBottom .moveul").find("li").eq(0).outerHeight(true) || 66;
	var siz1 = $(".bodyRightBottom .moveul").find("li").length;
	$(".bodyRightBottom .moveul").css('height', siz1 * rowHeight);
	$(".bodyRightBottom .moveul").html(function(index, value) {
		return value + value;
	})
	setInterval(function() {
		$(".bodyRightBottom .moveul").animate({
			top: "-=" + rowHeight
		}, 'slow', function() {
			if ($(".bodyRightBottom .moveul")[0].offsetTop <= -siz1 * rowHeight) {
				$(".bodyRightBottom .moveul").css('top', 0);
			}
		})
	}, 5300)
}());

// 成交动态滚动
(function() {
	setInterval(function() {
		$(".liushuihaoul .moveul").animate({
			top: "-=50"
		}, 'slow', function() {
			if ($(".liushuihaoul .moveul")[0].offsetTop <= -siz2 * 50 + 10) {
				$(".liushuihaoul .moveul").css('top', 0);
			}
		})
	}, 5000)
}());
//消息动态滚动
(function() {
	//消息滚动
	for (var i = 0; i < callMsg.length; i++) {
		$(".call .moveul").html((index, html) => {
			return html += `<li><i></i><span>${callMsg[i]}</span></li>`
		})
	}
	var siz3 = Math.ceil($(".call .moveul").find("li").length / 3);
	$(".call .moveul").css('height', $(".call .moveul").find("li").length * 78);
	$(".call .moveul").html(function(index, value) {
		return value + value;
	})
	setInterval(function() {
		$(".call .moveul").animate({
			top: "-=78"
		}, 'slow', function() {
			if ($(".call .moveul")[0].offsetTop <= -siz3 * 78) {
				$(".call .moveul").css('top', 0);
			}
		})
	}, 8000)
}());
//挂牌会员实时监控--仪表盘
(function() {
	var myChart1, myChart2, myChart3, myChart4, option1 = {},
		option2 = {},
		option3 = {},
		option4 = {};
	var data = [309, 300, 21, 234]; //总计排查单位，合格单位，黄线问题，基础问题
	function YB(id, names, datas) {
		var total = 325; //仪表盘总量
		names = echarts.init($("#" + id)[0]);
		option = {
			tooltip: {
				formatter: "{a} <br/>{b} : {c}%"
			},
			series: [{
				name: '周排查情况汇总',
				type: 'gauge',
				min: 0,
				max: 325,
				splitNumber: 13,
				radius: '100%',
				//center: ["15%", "50%"],
				detail: {
					formatter: '{value}%'
				},
				axisLine: { // 坐标轴线  
					lineStyle: { // 属性lineStyle控制线条样式  
						color: [
							[0.2, '#83B15A'],
							[0.4, '#DE9B32'],
							[0.6, '#50CDF6'],
							[0.8, '#1D9FF2'],
							[1, '#BF4746']
						],
						width: 10, //圆环宽度（坐标轴宽度）
					}
				},
				axisTick: { // 坐标轴小标记
					length: 15, // 属性length控制线长
					lineStyle: { // 属性lineStyle控制线条样式
						color: 'auto'
					}
				},
				splitLine: { // 分隔线
					length: 20, // 属性length控制线长
					lineStyle: { // 属性lineStyle（详见lineStyle）控制线条样式
						color: 'auto'
					}
				},
				axisLabel: {
					//backgroundColor: 'auto', //字块背景色
					//borderRadius: 20, //字块圆角
					color: 'auto', //文字颜色
					fontSize: 12,
					padding: 0,
					textShadowBlur: 20,
					textShadowOffsetX: 1,
					textShadowOffsetY: 1,
					textShadowColor: '#fff'
				},
				title: {
					// 其余属性默认使用全局文本样式，详见TEXTSTYLE
					fontWeight: 'bolder',
					fontSize: 16,
					fontStyle: 'italic',
					color: "#fff"
				},
				detail: {
					//其余属性默认使用全局文本样式，详见TEXTSTYLE
					formatter: function(value) {
						var num = (value / 325) * 100;
						return value + "\n\n占比 " + num.toFixed(2) + "%";
					},
					fontWeight: 'bolder',
					borderRadius: 3, //圆角
					backgroundColor: '#1D2088', //背景
					borderColor: '#00A0E9', //边框
					shadowBlur: 5,
					shadowColor: '#00A0E9',
					shadowOffsetX: 0,
					shadowOffsetY: 1,
					borderWidth: 2,
					//textBorderColor: '#62D4FB',
					textBorderWidth: 2,
					textShadowBlur: 2,
					textShadowColor: '#62D4FB',
					textShadowOffsetX: 0,
					textShadowOffsetY: 1,
					fontFamily: 'Arial',
					fontSize: 16,
					width: 30,
					height: 12,
					color: '#62D4FB',
					rich: {},
				},
				data: [{
					value: datas,
					name: '周排查'
				}]
			}]
		};
		names.setOption(option, true);
	}
	YB("yibiao1", myChart1, 309);
	YB("yibiao4", myChart4, 300);
	YB("yibiao2", myChart2, 21);
	YB("yibiao3", myChart3, 234);
	console.log(option1)
	var n = 0;
	run();

	//方块格子动画高亮特效
	$(".huiyuan").each(function(index) {
		var _this = $(this);
		let t = 0;
		setInterval(function() {
			n = Math.round(data[index] / 25); //每个方块数值25,13个方块总共325
			_this.find("li").each(function(i) {
				if (i <= n) {
					$(this).css("background", "#00A0E9");
					_this.find("li").eq(t).css("background", "#FBED14");
				} else {
					$(this).css("background", "#1D2088");
				}
			})
			t++;
			if (t > n + 1) t = 0;
		}, 300)
	})

	function run() {
		for (var i = 0; i < data.length; i++) {
			n = data[i] / 25; //每个方块数值25,13个方块总共325
			$(".huiyuan").eq(i).find("span").text(data[i])
			$(".huiyuan").eq(i).find("li").each(function(index) {
				if (index <= Math.floor(n)) {
					$(this).css("background", "#00A0E9")
				} else {
					$(this).css("background", "#1D2088")
				}
			})
		}
	}
}());
//消费数据分析
(function() {
	var myChart = echarts.init($("#jiage")[0]);
	var consumptionMonths = ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'];
	var consumptionSeries = [{
		name: '01县',
		data: [3260, 3340, 3410, 3380, 3450, 3520, 3490, 3560, 3620, 3580, 3650, 3720]
	}, {
		name: '02县',
		data: [2840, 2920, 3020, 2990, 3070, 3140, 3090, 3160, 3210, 3180, 3260, 3310]
	}, {
		name: '03县',
		data: [3320, 3410, 3530, 3470, 3550, 3620, 3590, 3660, 3720, 3680, 3760, 3820]
	}, {
		name: '04县',
		data: [2950, 3040, 3400, 3360, 3430, 3490, 3450, 3520, 3580, 3540, 3610, 3680]
	}];
	var lineColors = ['#46B05D', '#AF4B87', '#03A2E9', '#F5D52E'];
	var lineAreaColors = ['rgba(70,176,93,.28)', 'rgba(175,75,135,.28)', 'rgba(3,162,233,.28)', 'rgba(245,213,46,.28)'];
	var option = {
		textStyle: {
			color: "#000",
			fontSize: 30,
			fontWeight: "lighter"
		},
		tooltip: {
			trigger: 'axis',
			axisPointer: {
				type: 'cross',
				label: {
					backgroundColor: 'rgba(18, 65, 124, .9)'
				}
			}
		},
		legend: {
			data: consumptionSeries.map(function(item) {
				return item.name;
			}),
			top: 20,
			itemGap: 25,
			textStyle: {
				color: "#fff",
				fontSize: "18"
			}
		},
		grid: {
			top: "12%",
			left: '2%',
			right: '9%',
			bottom: '8%',
			containLabel: true
		},
		color: lineColors,
		xAxis: {
			type: 'category',
			name: "月份",
			nameGap: 20,
			nameTextStyle: {
				color: "#fff",
				fontSize: "16"
			},
			data: consumptionMonths,
			splitLine: {
				show: false
			},
			axisLine: {
				show: true,
				lineStyle: {
					color: "#fff",
					width: "2"
				},
				symbol: ["none", "arrow"],
				symbolSize: [8, 20],
				symbolOffset: [0, 16]
			},
			axisTick: {
				lineStyle: {
					color: "#fff",
					width: "2"
				}
			},
			axisLabel: {
				textStyle: {
					color: "#fff",
					fontSize: 20,
					fontWeight: "normal",
					interval: 0
				}
			}
		},
		yAxis: {
			type: 'value',
			min: 2600,
			max: 4000,
			interval: 200,
			boundaryGap: false,
			name: "消费金额",
			nameGap: 30,
			nameTextStyle: {
				color: "#fff",
				fontSize: "16"
			},
			axisLine: {
				show: true,
				lineStyle: {
					color: "#fff",
					width: "2"
				},
				symbol: ["none", "arrow"],
				symbolSize: [8, 20],
				symbolOffset: [0, 16]
			},
			splitLine: {
				show: true,
				lineStyle: {
					color: "#02416D",
					width: "0.5"
				}
			},
			axisTick: {
				lineStyle: {
					color: "#fff",
					width: "2"
				}
			},
			axisLabel: {
				textStyle: {
					color: "#fff",
					fontSize: 14,
					fontWeight: "normal",
					interval: 0
				}
			}
		},
		series: consumptionSeries.map(function(item, index) {
			return {
				name: item.name,
				type: 'line',
				smooth: false,
				symbol: 'circle',
				symbolSize: 8,
				lineStyle: {
					width: 3
				},
				emphasis: {
					focus: 'series'
				},
				areaStyle: {
					color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
						offset: 0,
						color: lineAreaColors[index]
					}, {
						offset: 1,
						color: 'rgba(0,0,0,0)'
					}])
				},
				data: item.data
			};
		})
	};
	myChart.setOption(option);
}());
//交易大厅实时监控--成交量饼图
(function() {
	var echartdata = [25, 60, 15, 0];
	var rich = {
		yellow: {
			color: "#ffc72b",
			fontSize: 18,
			padding: [2, 4],
			align: 'center'
		},
		total: {
			color: "#ffc72b",
			fontSize: 20,
			align: 'center'
		},
		white: {
			color: "#fff",
			align: 'center',
			fontSize: 16,
			padding: [10, 0]
		},
		blue: {
			color: '#49dff0',
			fontSize: 16,
			align: 'center'
		},
		hr: {
			borderColor: 'auto',
			width: '100%',
			borderWidth: 1,
			height: 0,
		}
	};
	var myChart = echarts.init($("#CJpie")[0]);
	var option = {
		tooltip: {
			trigger: 'item',
			formatter: "{b}: {c} ({d}%)"
		},
		series: [{
			type: 'pie',
			label: {
				fontSize: 24,
				normal: {
					color: "#fff",
					//formatter: '{b|{b}\n     {d}%}',
					formatter: function(params, ticket, callback) {
						var total = 0; //总数量
						var percent = 0; //占比
						echartdata.forEach(function(value, index) {
							total += value;
						});
						percent = ((params.value / total) * 100).toFixed(1);
						return '{white|' + params.name + '}\n\n{yellow|' + params.value + '}\n{blue|' + percent + '%}';
					},
					padding: [0, -50],
					rich: rich
				}
			},
			labelLine: {
				lineStyle: {
					width: 2
				},
				length: 20,
				length2: 50
			},
			radius: ['40%', '60%'],
			data: [{
				value: echartdata[0],
				itemStyle: {
					color: new echarts.graphic.RadialGradient(.5, .5, 1, [{
						offset: 0,
						color: '#D068F8'
					}, {
						offset: 1,
						color: '#403CB6'
					}]),
				},
				name: '副食占比'
			}, {
				value: echartdata[1],
				itemStyle: {
					color: new echarts.graphic.RadialGradient(.5, .5, 2, [{
						offset: 0,
						color: '#08C6D8'
					}, {
						offset: 1,
						color: '#0D55A2'
					}]),
				},
				name: '生鲜占比'
			}, {
				value: echartdata[2],
				itemStyle: {
					color: new echarts.graphic.RadialGradient(0.5, 0.5, 2, [{
						offset: 0,
						color: '#3AF990'
					}, {
						offset: 1,
						color: '#036172'
					}]),
				},
				name: '粮油占比'
			}, {
				value: echartdata[3],
				itemStyle: {
					color: new echarts.graphic.RadialGradient(0.5, 0.5, 2, [{
						offset: 0,
						color: '#FFF8A4'
					}, {
						offset: 1,
						color: '#FFEA02'
					}]),
				},
				name: '其他占比'
			}]
		}, ]
	};
	myChart.setOption(option);
	var total = 650;
	var n = 0;
	var totalBlocks = 13;
	run();

	function run() {
		for (var i = 0; i < echartdata.length; i++) {
			n = Math.round((echartdata[i] / 100) * totalBlocks);
			$(".CJL").eq(i).find("p").text(echartdata[i] + "%")
			$(".CJL").eq(i).find("li").each(function(index) {
				if (index >= (totalBlocks - n)) {
					$(this).css("background", "#00A0E9")
				} else {
					$(this).css("background", "#1D2088")
				}
			})
		}
	}
	$(".CJL").each(function(index) {
		var t = totalBlocks - 1;
		var _this = $(this);
		setInterval(function() {
			n = Math.round((echartdata[index] / 100) * totalBlocks);
			if (t < totalBlocks - n) {
				t = totalBlocks
			}
			if (n == 0) {
				_this.find("li").eq(totalBlocks - 1).css("background", "#FBED14")
			} else {
				_this.find("li").each(function(i) {
					if (i >= (totalBlocks - n)) {
						$(this).css("background", "#00A0E9")
						_this.find("li").eq(t).css("background", "#FBED14")
					} else {
						$(this).css("background", "#1D2088")
					}
				})
			}
			t--;
		}, 300)
	})
}());
//城销量实时监控--时间段成交量条形图
(function() {
	// 基于准备好的dom，初始化echarts实例
	var myChart = echarts.init($("#cjliang")[0]);
	var evaluationExamples = [
		"示例：早餐供应及时，排队时间较短",
		"示例：菜品温度稳定，整体口感较好",
		"示例：窗口服务响应快，补餐及时",
		"示例：口味反馈一般，需继续优化",
		"示例：食材新鲜度评价较高",
		"示例：高峰时段等待时间偏长",
		"示例：环境卫生和取餐秩序较好",
		"示例：晚餐菜品丰富度反馈提升"
	];
	// 指定图表的配置项和数据
	var option = {
		title: {
			text: ''
		},
		grid: {
			bottom: "15%",
			//left: 100,
		},
		xAxis: {
			type: 'category',
			name: "时间",
			data: ["00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00"],
			nameTextStyle: { //坐标轴名称样式
				color: "#fff",
				fontSize: "18",
				backgroundColor: "" //文字块背景色
			},
			nameGap: 25, //坐标名称与轴线的距离
			axisTick: { //坐标刻度线样式
				lineStyle: {
					color: "#fff"
				}
			},
			axisLabel: { //坐标轴刻度标签名样式
				color: "#fff",
				fontSize: "16",
				rotate: 0, //文字倾斜(当刻度标签名过长时使用)
				interval: 0 //显示全部                
			},
			axisLine: { //坐标轴线设置
				show: true,
				lineStyle: {
					color: "#fff",
					width: "2"
				},
				symbol: ["none", "arrow"], //坐标轴末端箭头
				symbolSize: [8, 20], //箭头高度和宽度
				symbolOffset: [0, 16] //箭头与轴线端点的距离
			},
			splitNumber: 7,
		},
		yAxis: {
			name: "评价值",
			splitNumber: 8,
			nameTextStyle: { //坐标轴名称样式
				color: "#fff",
				fontSize: "18",
				backgroundColor: "" //文字块背景色
			},
			nameGap: 25, //坐标名称与轴线的距离
			axisLine: { //坐标轴线设置
				show: true,
				lineStyle: {
					color: "#fff",
					width: "2"
				},
				symbol: ["none", "arrow"], //坐标轴末端箭头
				symbolSize: [8, 20], //箭头高度和宽度
				symbolOffset: [0, 16] //箭头与轴线端点的距离
			},
			axisTick: { //坐标刻度线样式
				lineStyle: {
					color: "#fff"
				}
			},
			axisLabel: { //坐标轴刻度标签名样式
				color: "#fff",
				fontSize: "18"
			},
			splitLine: { //垂直分割线
				show: true,
				lineStyle: {
					color: "#02416D",
					width: "0.5"
				}
			},
		},
		tooltip: {
			trigger: 'axis',
			axisPointer: {
				type: 'shadow'
			},
			formatter: function(params) {
				var point = params[0];
				return point.axisValue + '<br/>评价值：' + point.value + '<br/>' + evaluationExamples[point.dataIndex];
			}
		},
		series: [{
			name: '师生评价',
			type: 'bar',
			barWidth: 50,
			label: {
				show: true,
				color: "#fff",
				fontSize: 18,
				position: "top"
			},
			color: new echarts.graphic.LinearGradient(0, 0, 1, 0, [{
				offset: 0,
				color: '#3E3CB5'
			}, {
				offset: 1,
				color: '#D66BFD'
			}]),
			data: [820, 560, 730, 500, 720, 220, 730, 600]
		}]
	};
	// 使用刚指定的配置项和数据显示图表。
	myChart.setOption(option);
}());
