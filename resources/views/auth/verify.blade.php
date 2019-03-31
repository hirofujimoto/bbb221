@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('メールアドレス確認') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('ご指定のメールアドレスに確認用メールを再送しました。') }}
                        </div>
                    @endif

                    {{ __('ご利用に先立ってメールによる本人確認が必要となります。') }}
                    {{ __('メールが届かない場合は下の「再送」ボタンをお使いください。') }} 
                </div>
                <div class="card-footer">
                <a class="btn btn-primary" href="{{ route('verification.resend') }}">{{ __('再送') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
