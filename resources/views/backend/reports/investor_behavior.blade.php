@extends('backend.index')

@section('title', 'Phân tích Hành vi Nhà đầu tư')

@section('breadcrumb')
<li class="breadcrumb-item active">Báo cáo Hành vi Nhà đầu tư</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Filter -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('backend.reports.investor_behavior') }}">
                <div class="row align-items-center">
                    <div class="col-md-3 mb-2">
                        <div class="d-flex align-items-center">
                            <label class="mr-2 font-weight-bold mb-0 text-nowrap">Lọc theo kỳ:</label>
                            <select name="period" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Tháng này</option>
                                <option value="quarter" {{ $period == 'quarter' ? 'selected' : '' }}>Quý này</option>
                                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Năm nay</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="d-flex align-items-center">
                            <label class="mr-2 font-weight-bold mb-0 text-nowrap">Ngành nghề:</label>
                            <select name="industry" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="">Tất cả</option>
                                @foreach($industries as $ind)
                                    <option value="{{ $ind->id }}" {{ $industryId == $ind->id ? 'selected' : '' }}>{{ $ind->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="d-flex align-items-center">
                            <label class="mr-2 font-weight-bold mb-0 text-nowrap">Địa bàn:</label>
                            <select name="district" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="">Tất cả</option>
                                @foreach($districts as $dist)
                                    <option value="{{ $dist->id }}" {{ $districtId == $dist->id ? 'selected' : '' }}>{{ $dist->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2 text-md-right">
                        <button type="button" class="btn btn-sm btn-outline-info">
                            <i class="fa fa-envelope"></i> Đặt lịch tự động
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success ml-1">
                            <i class="fa fa-file-excel"></i> Xuất Excel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tóm tắt điều hành -->
    <h5 class="mb-3 font-weight-bold text-primary">Tóm tắt điều hành</h5>
    <div class="row">
        <div class="col-md-4">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $newGuestsCount }}</h3>
                    <p>NĐT đăng ký mới</p>
                </div>
                <div class="icon"><i class="fa fa-user-plus"></i></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalBookmarks }}</h3>
                    <p>Lượt quan tâm (Bookmark)</p>
                </div>
                <div class="icon"><i class="fa fa-heart"></i></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $conversionRate }}<sup style="font-size: 20px">%</sup></h3>
                    <p>Tỷ lệ chuyển đổi</p>
                </div>
                <div class="icon"><i class="fa fa-chart-line"></i></div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mt-3">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white font-weight-bold">
                    <i class="fa fa-bar-chart mr-1"></i> Top 5 Dự án được quan tâm nhất
                </div>
                <div class="card-body">
                    <canvas id="projectChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white font-weight-bold">
                    <i class="fa fa-pie-chart mr-1"></i> Lĩnh vực quan tâm
                </div>
                <div class="card-body">
                    <canvas id="industryChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Project Chart (Bar)
    const projectCtx = document.getElementById('projectChart').getContext('2d');
    new Chart(projectCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($projectChartData['labels']) !!},
            datasets: [{
                label: 'Lượt quan tâm',
                data: {!! json_encode($projectChartData['data']) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Industry Chart (Pie)
    const industryCtx = document.getElementById('industryChart').getContext('2d');
    new Chart(industryCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($industryChartData['labels']) !!},
            datasets: [{
                data: {!! json_encode($industryChartData['data']) !!},
                backgroundColor: [
                    '#ff6384', '#36a2eb', '#cc65fe', '#ffce56', '#4bc0c0'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
});
</script>
@endsection
