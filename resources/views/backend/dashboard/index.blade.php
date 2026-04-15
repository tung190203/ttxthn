@extends('backend.index')

@section('title')
DashBoard
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('style')
<style>
    .visit-card {
        border-radius: 12px;
        transition: transform 0.2s;
    }
    .visit-card:hover {
        transform: translateY(-5px);
    }
    .text-xs { font-size: 0.75rem; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Small boxes (Stat box) -->
        <!-- ./col -->
    </div>

    <!-- Website Visitor Stats -->
    <div class="card card-outline card-success shadow-none bg-transparent">
        <div class="card-header border-0 pl-0">
            <h3 class="card-title text-bold">
                <i class="fas fa-eye mr-2 text-success"></i>
                THỐNG KÊ TRUY CẬP WEBSITE
            </h3>
        </div>
        <div class="card-body p-0">
            <div class="row mb-4">
                <div class="col-lg-4 col-12">
                    <div class="small-box bg-white border shadow-sm visit-card">
                        <div class="inner">
                            <h3>{{ number_format($visitStats['unique_ips_today']) }}</h3>
                            <p class="text-muted text-sm mb-0">Địa chỉ IP truy cập (Hôm nay)</p>
                            <span class="text-xs text-success"><i class="fas fa-fingerprint mr-1"></i>Mỗi IP chỉ được tính 1 lần/ngày</span>
                        </div>
                        <div class="icon">
                            <i class="fas fa-network-wired text-light" style="opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="small-box bg-white border shadow-sm visit-card" style="border-left: 4px solid #f39c12 !important;">
                        <div class="inner">
                            <h3>{{ number_format($visitStats['bots_today']) }}</h3>
                            <p class="text-muted text-sm mb-0">Lượt Bot/Crawl (Hôm nay)</p>
                            <span class="text-xs text-warning"><i class="fas fa-robot mr-1"></i>Dự đoán dựa trên User-Agent</span>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-secret text-light" style="opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="small-box bg-white border shadow-sm visit-card">
                        <div class="inner">
                            <h3>{{ $visitStats['unique_ips_today'] > 0 ? round(($visitStats['bots_today'] / $visitStats['unique_ips_today']) * 100, 1) : 0 }}%</h3>
                            <p class="text-muted text-sm mb-0">Tỷ lệ Bot trong ngày</p>
                            <div class="progress progress-xxs mt-2">
                                <div class="progress-bar bg-warning" style="width: {{ $visitStats['unique_ips_today'] > 0 ? ($visitStats['bots_today'] / $visitStats['unique_ips_today']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="fas fa-percentage text-light" style="opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card card-outline card-success shadow-sm">
                        <div class="card-header border-0">
                            <h3 class="card-title text-bold text-sm">
                                <i class="fas fa-history mr-1 text-success"></i>
                                Biểu đồ truy cập (7 ngày gần nhất)
                            </h3>
                        </div>
                        <div class="card-body">
                            <div style="height: 250px; position: relative;">
                                <canvas id="visitor-activity-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card card-outline card-info shadow-none bg-transparent">
        <div class="card-header border-0 pl-0">
            <h3 class="card-title text-bold">
                <i class="fas fa-robot mr-2 text-info"></i>
                BÁO CÁO & THỐNG KÊ AI CHATBOT
            </h3>
        </div>
        <div class="card-body p-0">
            <!-- AI Usage Statistics Overview -->
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white border shadow-sm">
                        <div class="inner">
                            <h3 id="ai-total-sessions">0</h3>
                            <p class="text-muted text-sm">Tổng phiên hội thoại</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-comments text-light" style="opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white border shadow-sm">
                        <div class="inner">
                            <h3 id="ai-total-messages">0</h3>
                            <p class="text-muted text-sm">Tổng số tin nhắn</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-comment-dots text-light" style="opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white border shadow-sm">
                        <div class="inner">
                            <h3 id="ai-total-tokens">0</h3>
                            <p class="text-muted text-sm">Tổng Tokens sử dụng</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-microchip text-light" style="opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-white border shadow-sm">
                        <div class="inner">
                            <h3 id="ai-total-cost">$0.00</h3>
                            <p class="text-muted text-sm">Chi phí ước tính (USD)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign text-light" style="opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Analytics Charts -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card card-outline card-primary shadow-sm h-100">
                        <div class="card-header border-0">
                            <h3 class="card-title text-bold text-sm">
                                <i class="fas fa-chart-line mr-1 text-primary"></i>
                                Hoạt động AI Bot (7 ngày qua)
                            </h3>
                        </div>
                        <div class="card-body">
                            <div id="ai-daily-chart-container" style="height: 300px; position: relative;">
                                <canvas id="ai-daily-activity-chart"></canvas>
                                <div class="ai-no-data d-none text-center p-5" style="position: absolute; top: 0; width: 100%;">
                                    <i class="fas fa-chart-line fa-3x text-light mb-3"></i>
                                    <p class="text-muted">Chưa có dữ liệu hoạt động trong khoảng thời gian này</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-outline card-success shadow-sm h-100">
                        <div class="card-header border-0">
                            <h3 class="card-title text-bold text-sm">
                                <i class="fas fa-pie-chart mr-1 text-success"></i>
                                Phân bổ ý định (Intents)
                            </h3>
                        </div>
                        <div class="card-body">
                            <div id="ai-intents-chart-container" style="height: 250px; position: relative;">
                                <canvas id="ai-intents-chart"></canvas>
                                <div class="ai-no-data d-none text-center p-4" style="position: absolute; top: 0; width: 100%;">
                                    <i class="fas fa-comment-slash fa-2x text-light mb-3"></i>
                                    <p class="text-muted">Chưa có dữ liệu ý định</p>
                                </div>
                            </div>
                            <div id="ai-intents-legend" class="mt-4" style="max-height: 150px; overflow-y: auto;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-7 connectedSortable">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Hoạt động hệ thống
                    </h3>
                    <div class="card-tools">
                        <select id="chart-range-selector" class="form-control form-control-sm" style="width: 150px;">
                            <option value="3" {{ $range == 3 ? 'selected' : '' }}>3 tháng gần nhất</option>
                            <option value="6" {{ $range == 6 ? 'selected' : '' }}>6 tháng gần nhất</option>
                            <option value="12" {{ $range == 12 ? 'selected' : '' }}>12 tháng gần nhất</option>
                            <option value="24" {{ $range == 24 ? 'selected' : '' }}>24 tháng gần nhất</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content p-0">
                        <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- Right col -->
        <section class="col-lg-5 connectedSortable">
            <div class="card bg-gradient-info h-100">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-th mr-1"></i>
                        Phân bổ dự án theo lĩnh vực
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7">
                            <canvas id="project-dist-canvas" height="300" style="height: 300px;"></canvas>
                        </div>
                        <div class="col-md-5">
                            <ul id="project-dist-legend" class="chart-legend clearfix" style="max-height: 280px; overflow-y: auto; list-style: none; padding-left: 0; margin-top: 10px;">
                                <!-- Legend items injected via JS -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-1"></i>
                        Nhật ký hoạt động
                    </h3>
                    <div class="card-tools">
                        <form id="activity-log-filter" action="{{ route('backend_dashboard') }}" method="GET" class="form-inline">
                            <input type="hidden" name="range" value="{{ $range }}">
                            <select name="event_log" class="form-control form-control-sm mr-2">
                                <option value="">Tất cả sự kiện</option>
                                <option value="created" {{ request('event_log') == 'created' ? 'selected' : '' }}>Thêm mới</option>
                                <option value="updated" {{ request('event_log') == 'updated' ? 'selected' : '' }}>Cập nhật</option>
                                <option value="deleted" {{ request('event_log') == 'deleted' ? 'selected' : '' }}>Xóa</option>
                                <option value="logged in" {{ request('event_log') == 'logged in' ? 'selected' : '' }}>Đăng nhập</option>
                                <option value="logged out" {{ request('event_log') == 'logged out' ? 'selected' : '' }}>Đăng xuất</option>
                            </select>
                            <div class="input-group input-group-sm" style="width: 200px;">
                                <input type="text" name="search_log" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search_log') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="activity-log-container">
                    @include('backend.dashboard.partials._activity_log_table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('backend_assets/vendor/chart/Chart.min.js') }}"></script>
<script>
    $(function() {
        'use strict'

        // Monthly Activity Chart
        var activityChartCanvas = $('#revenue-chart-canvas').get(0).getContext('2d')
        var activityChartData = {
            labels: @json($chartLabels),
            datasets: [{
                    label: 'Dự án mới',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    pointRadius: false,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: @json($projectChartData)
                },
                {
                    label: 'Tin tức mới',
                    backgroundColor: 'rgba(210, 214, 222, 1)',
                    borderColor: 'rgba(210, 214, 222, 1)',
                    pointRadius: false,
                    pointColor: 'rgba(210, 214, 222, 1)',
                    pointStrokeColor: '#c1c7d1',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data: @json($postChartData)
                },
                {
                    label: 'Người dùng mới',
                    backgroundColor: 'rgba(40, 167, 69, 0.9)',
                    borderColor: 'rgba(40, 167, 69, 0.8)',
                    pointRadius: false,
                    pointColor: '#28a745',
                    pointStrokeColor: 'rgba(40, 167, 69, 1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(40, 167, 69, 1)',
                    data: @json($guestChartData)
                },
                {
                    label: 'Cẩm nang mới',
                    backgroundColor: 'rgba(255, 193, 7, 0.9)',
                    borderColor: 'rgba(255, 193, 7, 0.8)',
                    pointRadius: false,
                    pointColor: '#ffc107',
                    pointStrokeColor: 'rgba(255, 193, 7, 1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(255, 193, 7, 1)',
                    data: @json($investmentGuideChartData)
                }
            ]
        }

        var activityChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: true
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false
                    }
                }],
                yAxes: [{
                    gridLines: {
                        display: false
                    }
                }]
            }
        }

        new Chart(activityChartCanvas, {
            type: 'bar',
            data: activityChartData,
            options: activityChartOptions
        })

        // Project Distribution Pie Chart
        var pieCanvas = $('#project-dist-canvas').get(0).getContext('2d')
        var industryLabels = @json($industryLabels);
        
        // Tạo dải màu tự động tránh trùng lặp
        var generateColors = function(count) {
            var colors = [];
            for (var i = 0; i < count; i++) {
                var hue = (i * 137.508) % 360;
                colors.push('hsl(' + Math.floor(hue) + ', 75%, 55%)');
            }
            return colors;
        };

        var pieData = {
            labels: industryLabels,
            datasets: [{
                data: @json($industryData),
                backgroundColor: generateColors(industryLabels.length)
            }]
        }
        var pieOptions = {
            legend: {
                display: false
            },
            maintainAspectRatio: false,
            responsive: true
        }

        var pieChart = new Chart(pieCanvas, {
            type: 'pie',
            data: pieData,
            options: pieOptions
        })

        // Generate Custom Legend
        var legendContainer = $('#project-dist-legend');
        var colors = pieData.datasets[0].backgroundColor;
        pieData.labels.forEach(function(label, index) {
            var color = colors[index % colors.length];
            var li = $('<li class="nav-item"></li>');
            li.css({
                'display': 'flex',
                'align-items': 'center',
                'color': '#fff',
                'margin-bottom': '8px',
                'cursor': 'pointer',
                'font-size': '13px'
            });

            var colorBox = $('<span class="mr-2"></span>');
            colorBox.css({
                'display': 'inline-block',
                'width': '12px',
                'height': '12px',
                'background-color': color,
                'border': '1px solid rgba(255,255,255,0.2)'
            });

            var labelSpan = $('<span></span>').text(label);
            labelSpan.css({
                'flex': '1',
                'overflow': 'hidden',
                'text-overflow': 'ellipsis',
                'white-space': 'nowrap'
            });

            li.append(colorBox).append(labelSpan);
            li.attr('title', label);

            li.on('click', function() {
                var meta = pieChart.getDatasetMeta(0);
                meta.data[index].hidden = !meta.data[index].hidden;
                pieChart.update();
                $(this).css('opacity', meta.data[index].hidden ? '0.5' : '1');
                $(this).find('span:last-child').css('text-decoration', meta.data[index].hidden ? 'line-through' : 'none');
            });

            legendContainer.append(li);
        });

        // Chart Range Selector Listener
        $('#chart-range-selector').on('change', function() {
            var range = $(this).val();
            var url = new URL(window.location.href);
            url.searchParams.set('range', range);
            window.location.href = url.href;
        });

        // AJAX for Activity Log
        function fetchActivities(url) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $('#activity-log-container').html(data);
                },
                error: function() {
                    toastr.error('Không thể tải nhật ký hoạt động.');
                }
            });
        }

        $('#activity-log-filter').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            var url = $(this).attr('action') + '?' + formData;
            fetchActivities(url);
            window.history.pushState({}, '', url);
        });

        $('select[name="event_log"]').on('change', function() {
            $('#activity-log-filter').submit();
        });

        $(document).on('click', '#activity-log-container .pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            fetchActivities(url);
            window.history.pushState({}, '', url);
        });

        // AI Bot Monitoring logic
        var aiMonitorTimeout;

        // Advanced AI Stats
        var dailyActivityChart;
        var intentsChart;

        function updateAdvancedAiStats() {
            $.ajax({
                url: "{{ route('backend_ai_monitor_advanced_stats') }}",
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        var data = response.data;
                        
                        // 1. Update Overview Stats
                        var totals = data.overview.totals || {};
                        $('#ai-total-sessions').text(totals.active_sessions || 0);
                        $('#ai-total-messages').text(totals.total_messages || 0);
                        $('#ai-total-tokens').text((totals.total_tokens || 0).toLocaleString());
                        $('#ai-total-cost').text('$' + (totals.total_cost_usd || 0).toFixed(4));

                        // 2. Update Daily Activity Chart
                        var dailySeries = data.daily.series || [];
                        var dailyContainer = $('#ai-daily-chart-container');
                        
                        if (dailySeries.length === 0) {
                            dailyContainer.find('canvas').addClass('d-none');
                            dailyContainer.find('.ai-no-data').removeClass('d-none');
                        } else {
                            dailyContainer.find('canvas').removeClass('d-none');
                            dailyContainer.find('.ai-no-data').addClass('d-none');
                            
                            var labels = dailySeries.map(item => item.date);
                            var messages = dailySeries.map(item => item.messages);
                            var sessions = dailySeries.map(item => item.sessions);

                            if (dailyActivityChart) {
                                dailyActivityChart.destroy();
                            }

                            dailyActivityChart = new Chart($('#ai-daily-activity-chart').get(0).getContext('2d'), {
                                type: 'line',
                                data: {
                                    labels: labels,
                                    datasets: [
                                        {
                                            label: 'Tin nhắn',
                                            borderColor: '#007bff',
                                            backgroundColor: 'rgba(0, 123, 255, 0.1)',
                                            data: messages,
                                            fill: true,
                                            tension: 0.4
                                        },
                                        {
                                            label: 'Phiên hội thoại',
                                            borderColor: '#28a745',
                                            backgroundColor: 'rgba(40, 167, 69, 0.1)',
                                            data: sessions,
                                            fill: true,
                                            tension: 0.4
                                        }
                                    ]
                                },
                                options: {
                                    maintainAspectRatio: false,
                                    responsive: true,
                                    title: { display: false },
                                    legend: { position: 'top' },
                                    tooltips: { mode: 'index', intersect: false },
                                    hover: { mode: 'nearest', intersect: true },
                                    scales: {
                                        xAxes: [{ gridLines: { display: false }, ticks: { fontSize: 10 } }],
                                        yAxes: [{ gridLines: { color: 'rgba(0,0,0,0.05)' }, ticks: { beginAtZero: true, fontSize: 10 } }]
                                    }
                                }
                            });
                        }

                        // 3. Update Intents Chart
                        var intentsData = data.intents.intents || [];
                        var intentContainer = $('#ai-intents-chart-container');
                        
                        if (intentsData.length === 0) {
                            intentContainer.find('canvas').addClass('d-none');
                            intentContainer.find('.ai-no-data').removeClass('d-none');
                            $('#ai-intents-legend').html('');
                        } else {
                            intentContainer.find('canvas').removeClass('d-none');
                            intentContainer.find('.ai-no-data').addClass('d-none');
                            
                            var intentLabels = intentsData.map(item => item.intent);
                            var intentCounts = intentsData.map(item => item.count);
                            var intentColors = generateColors(intentsData.length);

                            if (intentsChart) {
                                intentsChart.destroy();
                            }

                            intentsChart = new Chart($('#ai-intents-chart').get(0).getContext('2d'), {
                                type: 'doughnut',
                                data: {
                                    labels: intentLabels,
                                    datasets: [{
                                        data: intentCounts,
                                        backgroundColor: intentColors
                                    }]
                                },
                                options: {
                                    maintainAspectRatio: false,
                                    responsive: true,
                                    legend: { display: false },
                                    cutoutPercentage: 70
                                }
                            });

                            // Generate Intent Legend
                            var legendHtml = '<div class="row">';
                            intentsData.forEach((item, index) => {
                                legendHtml += `
                                    <div class="col-6 mb-2">
                                        <div class="d-flex align-items-center">
                                            <div style="width: 12px; height: 12px; background-color: ${intentColors[index]}; margin-right: 8px; border-radius: 2px;"></div>
                                            <div class="text-truncate" title="${item.intent}" style="font-size: 11px;">
                                                <span class="text-bold">${item.percentage.toFixed(1)}%</span> ${item.intent}
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                            legendHtml += '</div>';
                            $('#ai-intents-legend').html(legendHtml);
                        }
                    }
                }
            });
        }

        updateAdvancedAiStats();

        // Website Visitor Activity Chart
        var visitorChartCanvas = $('#visitor-activity-chart').get(0).getContext('2d');
        new Chart(visitorChartCanvas, {
            type: 'bar',
            data: {
                labels: @json($visitChartLabels),
                datasets: [
                    {
                        label: 'Địa chỉ IP thực',
                        backgroundColor: '#28a745',
                        data: @json($visitChartData).map((v, i) => v - @json($botChartData)[i])
                    },
                    {
                        label: 'Địa chỉ IP Bot',
                        backgroundColor: '#f39c12',
                        data: @json($botChartData)
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    xAxes: [{ stacked: true, gridLines: { display: false } }],
                    yAxes: [{ stacked: true, gridLines: { color: 'rgba(0,0,0,0.05)' } }]
                },
                legend: { position: 'top' }
            }
        });
    })
</script>
@endsection
```