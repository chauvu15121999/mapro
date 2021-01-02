@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div style="margin-top: 100px; border: 1px solid black;" class="card">
                <div class="card-header"><h2>Vui lòng xác thực email để tiếp tục</h2></div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Mail xác thực đã được gửi , vui lòng kiểm tra mail của bạn.') }}
                        </div>
                    @endif

                    <p>{{ __('Trước khi tiếp tục, vui lòng kiểm tra email của bạn để biết liên kết xác minh.') }}</p>
                    <p>{{ __('Nếu bạn chưa nhận được mail :') }}</p>
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button style="margin-top: 20px; margin-left: 150px;" type="submit" class="btn btn-primary">{{ __('Lấy mail xác nhận') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
