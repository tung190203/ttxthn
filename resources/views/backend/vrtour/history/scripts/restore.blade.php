<script>
    (function() {
        const $modal = $('#restore-modal');
        if (!$modal.length) return;

        const csrf = '{{ csrf_token() }}';
        let versionId = null,
            token = null;

        const post = async (url, body) => {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify(body),
            });
            const json = await res.json().catch(() => ({}));
            if (!res.ok) {
                const err = json.errors ? Object.values(json.errors)[0][0] : (json.message || 'Có lỗi xảy ra.');
                throw new Error(err);
            }
            return json;
        };

        const show = (id, msg) => $(id).text(msg).removeClass('d-none');

        $(document).on('click', '.btn-restore', function() {
            versionId = this.dataset.id;
            token = null;
            $('#restore-title').text('v' + this.dataset.number);
            $('#restore-reason').val('');
            $('#restore-error1, #restore-error2').addClass('d-none');
            $('#restore-step1, #restore-next').removeClass('d-none');
            $('#restore-step2, #restore-confirm').addClass('d-none');
            $modal.modal('show');
        });

        $('#restore-next').on('click', async function() {
            const reason = $('#restore-reason').val().trim();
            $('#restore-error1').addClass('d-none');
            this.disabled = true;
            try {
                const r = await post('{{ route('backend_vrtour_history_restore_prepare') }}', {
                    version_id: versionId,
                    reason
                });
                token = r.token;
                $('#restore-src').text('v' + r.source.number + ' (' + r.source.at + ')');
                $('#sum-add').text(r.summary.restored_back);
                $('#sum-del').text(r.summary.will_delete);
                $('#sum-mod').text(r.summary.will_revert);
                $('#restore-reason-view').text(reason);

                const rt = r.retention;
                if (rt && rt.at_cap && rt.oldest) {
                    $('#restore-prune-warning')
                        .text('Tour đã lưu ' + rt.count + '/' + rt.max +
                            ' phiên bản. Khôi phục sẽ xóa vĩnh viễn bản cũ nhất (v' +
                            rt.oldest.number + ', ' + rt.oldest.at + ').')
                        .removeClass('d-none');
                } else {
                    $('#restore-prune-warning').addClass('d-none');
                }

                $('#restore-step1, #restore-next').addClass('d-none');
                $('#restore-step2, #restore-confirm').removeClass('d-none');
            } catch (e) {
                show('#restore-error1', e.message);
            } finally {
                this.disabled = false;
            }
        });

        $('#restore-confirm').on('click', async function() {
            this.disabled = true;
            try {
                const r = await post('{{ route('backend_vrtour_history_restore_confirm') }}', {
                    token
                });
                window.location.href = r.redirect;
            } catch (e) {
                show('#restore-error2', e.message);
                this.disabled = false;
            }
        });
    })();
</script>