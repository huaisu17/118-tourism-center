import re

dot_file = r'D:/school_work/big-data-view/web/118_center/data_view/118-tourism-center/草牧商品交易数据可视化大屏HTML模板/118-tourism-center/data_view/docs/ER图.md'
output_file = r'D:/school_work/big-data-view/web/118_center/data_view/118-tourism-center/草牧商品交易数据可视化大屏HTML模板/118-tourism-center/data_view/docs/er-diagram'

with open(dot_file, 'r', encoding='utf-8') as f:
    content = f.read()

# Extract DOT code between ```dot and ```
dot_code = re.search(r'```dot\n(.*?)\n```', content, re.DOTALL)
if dot_code:
    dot_source = dot_code.group(1)
    print("DOT code extracted successfully")
    print(f"Length: {len(dot_source)} chars")

    # Also save as standalone .dot file
    dot_standalone = dot_file.replace('.md', '.dot')
    with open(dot_standalone, 'w', encoding='utf-8') as f:
        f.write(dot_source)
    print(f"Standalone DOT file saved to: {dot_standalone}")

    # Try to render with graphviz python library
    try:
        from graphviz import Source
        src = Source(dot_source)
        src.render(output_file, format='png', cleanup=False)
        print(f"PNG rendered successfully: {output_file}.png")
    except Exception as e:
        print(f"Graphviz rendering failed: {e}")
        print("Please install Graphviz binary from https://graphviz.org/download/")
else:
    print("DOT code not found in markdown file")
