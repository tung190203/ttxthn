<footer class="main-footer py-2">
    @php
        $backendSiteName = \App\Models\Setting::getSettingByKey('site_name', 'TTXT');
    @endphp

    <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between">
        <div class="d-flex flex-column flex-md-row align-items-center text-center text-md-left">
            <div>
                <strong>Copyright &copy; 2014-{{ date('Y') }}
                    <a href="{{ route('backend_dashboard') }}">{{ $backendSiteName }}</a>.</strong>
                <span class="text-muted">All rights reserved.</span>
            </div>

            <div class="mt-2 mt-md-0 ml-md-3">
                <span class="badge badge-light border px-3 py-2 text-muted font-weight-normal">
                    <i class="fas fa-layer-group text-primary mr-1"></i>
                    Sản phẩm xây dựng trên nền tảng
                    <a href="https://hm360.vn/" target="_blank" rel="noopener noreferrer" class="font-weight-bold text-primary">HM360</a>
                </span>
            </div>
        </div>

        <div class="mt-2 mt-lg-0">
            <span class="badge badge-light border px-3 py-2 text-muted">
                Version {{ config('cms.version') }}
            </span>
        </div>
    </div>
</footer>
