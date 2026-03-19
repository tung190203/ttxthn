@extends('backend.index')

@section('title')
DashBoard
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $quantityProjects }}</h3>
                    <p>Dự án</p>
                </div>
                <div class="icon">
                    <i class="fas fa-industry"></i>
                </div>
                <a href="{{ route('backend_project') }}" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $quantityUser }}</h3>
                    <p>Người dùng hệ thống</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('backend_guest') }}" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $quantityPost }}</h3>
                    <p>Tin tức & Bài viết</p>
                </div>
                <div class="icon">
                    <i class="far fa-newspaper"></i>
                </div>
                <a href="{{ route('backend_post') }}" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $quantityInvestmentGuide }}</h3>
                    <p>Cẩm nang đầu tư</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <a href="{{ route('backend_investment_guide') }}" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
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
    })
</script>
@endsection
```