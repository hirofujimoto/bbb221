@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('新規スレッド') }}</div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card-body">
                    <form method="POST" action="{{ route('article.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="title" class="col-md-1 col-form-label text-md-right">{{ __('題名') }}</label>
                            <div class="col-md-10">
                                <input id="title" type="text" class="form-control" name="title" val="{{ old('title') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="message" class="col-md-1 col-form-label text-md-right">{{ __('本文') }}</label>
                            <div class="col-md-10">
                                <textarea id="message" class="form-control" name="message" rows="20" required >
                                {{ old('message') }}
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
