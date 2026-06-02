@extends('frontend.index')

@section('content')
    <section class="page-title py-5">
        <div class="container">
            <h1 class="section__title mb-4">{{ __('app.reset_password') }}</h1>

            <div class="row justify-content-center">
                <div class="col-md-6">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('guest_password_update') }}" class="bg-white p-4 rounded shadow-sm">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label class="form-label">{{ __('app.email_address') }}</label>
                            <input class="form-control" type="email" name="email" value="{{ old('email', $email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('app.new_password') }}</label>
                            <input class="form-control" type="password" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('app.confirm_password') }}</label>
                            <input class="form-control" type="password" name="password_confirmation" required>
                        </div>

                        <button class="btn btn-primary w-100" type="submit">{{ __('app.reset_password') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
