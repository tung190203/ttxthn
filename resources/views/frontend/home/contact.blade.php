@extends('frontend.index')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="bg-white p-5 rounded shadow-sm">
                <h2 class="mb-4 text-primary">Liên hệ</h2>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="fullname" class="form-label">Họ và tên</label>
                        <input type="text" name="fullname" id="fullname"
                               class="form-control @error('fullname') is-invalid @enderror"
                               placeholder="Nhập họ và tên" value="{{ old('fullname') }}" required>
                        @error('fullname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Địa chỉ Email</label>
                        <input type="email" name="email" id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="you@example.com" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" id="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               placeholder="Nhập số điện thoại" value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="project" class="form-label">Dự án bạn quan tâm</label>
                        <select name="project" id="project" class="form-select @error('project') is-invalid @enderror" required>
                            <option value="">-- Chọn dự án --</option>
                            <option value="Thịnh Vượng" {{ old('project') == 'Thịnh Vượng' ? 'selected' : '' }}>Khu đô thị Thịnh Vượng</option>
                            <option value="TechPark" {{ old('project') == 'TechPark' ? 'selected' : '' }}>Dự án TechPark Bình Dương</option>
                            <option value="Riverside" {{ old('project') == 'Riverside' ? 'selected' : '' }}>Căn hộ Riverside</option>
                        </select>
                        @error('project')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Lời nhắn</label>
                        <textarea name="message" id="message" rows="4"
                                  class="form-control @error('message') is-invalid @enderror"
                                  placeholder="Bạn đang quan tâm điều gì...">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary text-white">Gửi liên hệ</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('bottom')

@endpush
