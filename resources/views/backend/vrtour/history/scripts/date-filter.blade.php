<script>
    (function() {
        const $fromDate = $('#from_date');
        const $toDate = $('#to_date');
        if (!$fromDate.length || !$toDate.length) return;

        function updateDateConstraints() {
            const fromValue = $fromDate.val();
            const toValue = $toDate.val();

            $toDate.attr('min', fromValue || '');
            $fromDate.attr('max', toValue || '');

            if (fromValue && toValue && toValue < fromValue) {
                $toDate.val(fromValue);
            }
        }

        $fromDate.on('change', updateDateConstraints);
        $toDate.on('change', updateDateConstraints);
        updateDateConstraints();
    })();
</script>