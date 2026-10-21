<script>
    (function() {
        const boxes = () => [...document.querySelectorAll('.chk-compare')];
        const btn = document.getElementById('btn-compare');
        if (!btn) return;

        function sync() {
            const checked = boxes().filter(c => c.checked);
            boxes().forEach(c => c.disabled = checked.length >= 2 && !c.checked);

            document.getElementById('compare-count').textContent = checked.length;
            document.getElementById('compare-a').value = checked[0]?.value ?? '';
            document.getElementById('compare-b').value = checked[1]?.value ?? '';
            btn.disabled = checked.length !== 2;
        }

        document.addEventListener('change', e => {
            if (e.target.classList.contains('chk-compare')) sync();
        });
        sync();
    })();
</script>