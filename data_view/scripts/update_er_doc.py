import re

md_file = r'D:/school_work/big-data-view/web/118_center/data_view/118-tourism-center/草牧商品交易数据可视化大屏HTML模板/118-tourism-center/data_view/docs/ER图.md'
dot_file = r'D:/school_work/big-data-view/web/118_center/data_view/118-tourism-center/草牧商品交易数据可视化大屏HTML模板/118-tourism-center/data_view/docs/ER图.dot'

with open(dot_file, 'r', encoding='utf-8') as f:
    dot_code = f.read()

with open(md_file, 'r', encoding='utf-8') as f:
    md_content = f.read()

# Replace DOT code block
new_md = re.sub(
    r'```dot\n.*?\n```',
    f'```dot\n{dot_code}\n```',
    md_content,
    flags=re.DOTALL
)

# Update entity count and relationship attributes explanation
new_md = re.sub(
    r'\| 符号 \| 含义 \|\n\|------\|------\|\n\| ▭ 矩形 \| \*\*实体\*\*（表） \|\n\| ◯ 椭圆 \| \*\*属性\*\*（字段），其中<u>下划线</u>表示\*\*主键\*\* \|\n\| ◇ 菱形 \| \*\*关系\*\*（实体之间的关联） \|\n\| 连线上的 \*\*1 / N / M\*\* \| \*\*基数\*\*（一对一 / 一对多 / 多对多） \|',
    '''| 符号 | 含义 |
|------|------|
| ▭ 矩形 | **实体**（表） |
| ◯ 椭圆（实线） | **属性**（字段） |
| ◇ 菱形 | **关系**（实体之间的关联） |
| ◯ 椭圆（虚线） | **关系属性**（附加在关系上的字段） |
| 连线上的 **1 / n / m** | **基数**（一对一 / 一对多 / 多对多） |''',
    new_md
)

with open(md_file, 'w', encoding='utf-8') as f:
    f.write(new_md)

print("ER图.md updated successfully")
