@extends('backend.index')

@section('title', 'Không có quyền truy cập')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-lock fa-5x text-warning mb-4"></i>
                        <h3>{{ $message }}</h3>
                        <p class="text-muted mt-3">Vui lòng liên hệ quản trị viên để được cấp quyền truy cập.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection