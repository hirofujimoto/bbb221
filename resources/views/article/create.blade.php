@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('新規スレッド') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('article.store') }}">
                        @csrf

                        <div class="form-group row">
                            @if ($errors->has('title'))
                                <div>
                                <p>{{ $errors->first('title')}}</p>
                                </div>
                            @endif
                            <label for="title" class="col-md-1 col-form-label text-md-right">{{ __('タイトル') }}</label>

                            <div class="col-md-10">
                                <input id="title" type="text" class="form-control" name="title" >
                            </div>
                        </div>

                        <div class="form-group row">
                            @if ($errors->has('message'))
                                <div>
                                <p>{{ $errors->first('message' )}}</p>
                                </div>
                            @endif
                            <label for="message" class="col-md-1 col-form-label text-md-right">{{ __('内容') }}</label>
                            <div class="col-md-10">
                                <textarea id="message" class="form-control" name="message" rows="20" >
                                </textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-2 offset-md-1">
                                <button type="submit" class="btn btn-primary form-control">
                                    {{ __('登録') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
