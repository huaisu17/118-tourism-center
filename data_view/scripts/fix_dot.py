import re

dot_file = r'D:/school_work/big-data-view/web/118_center/data_view/118-tourism-center/草牧商品交易数据可视化大屏HTML模板/118-tourism-center/data_view/docs/ER图.dot'

with open(dot_file, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix: label=X" -> label="X"
# Pattern: label=([^"\s][^"]*")  (label= followed by non-quote char, ending with quote)
content = re.sub(r'label=([^"\s][^"]*")', r'label="\1', content)

with open(dot_file, 'w', encoding='utf-8') as f:
    f.write(content)

print("Fixed DOT file")

# Verify
with open(dot_file, 'r', encoding='utf-8') as f:
    lines = f.readlines()
    for i, line in enumerate(lines, 1):
        if 'label=' in line and 'label="' not in line:
            print(f"Line {i} still has bad label: {line.strip()}")
