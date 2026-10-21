<script>
    (function() {
        const $projectSelect = $('#slt_vrtour');
        const $projectForm = $('#version-project-form');
        const $viewButton = $('#btn-view-version-history');

        function updateViewButton() {
            if (!$projectSelect.length || !$viewButton.length) return;
            $viewButton.prop('disabled', !$projectSelect.val());
        }

        $projectSelect.on('change', updateViewButton);
        updateViewButton();

        $projectForm.on('submit', function(event) {
            if (!$projectSelect.val()) {
                event.preventDefault();
                $projectSelect.select2('open');
            }
        });
    })();
</script>