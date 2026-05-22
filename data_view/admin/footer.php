</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// 确认删除
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function(e) {
        if (!confirm('确定要删除这条记录吗？此操作不可恢复。')) {
            e.preventDefault();
        }
    });
});
</script>
</body>
</html>
