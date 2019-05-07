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
                    <form method="POST" action="{{ route('article.store') }}" enctype="multipart/form-data" >
                        @csrf

                        <div class="form-group row">
                            <label for="title" class="col-md-1 col-form-label text-md-right">{{ __('題名') }}</label>
                            <div class="col-md-10">
                                <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="message" class="col-md-1 col-form-label text-md-right">{{ __('本文') }}</label>
                            <div class="col-md-10">
                                <textarea id="message" class="form-control" name="message" rows="20" >{{ old('message') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="imagefile" class="col-md-1 col-form-label text-md-right">{{ __('画像') }}</label>
                            <div class="col-md-6">
                                <input id="imagefile" type="file" class="form-control" name="imagefile">
                            </div>
                            <div class="col-md-4">
                                {{ __('画像はjpg，png。サイズは 縦横1200px までです。') }}
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-2 offset-md-1">
                                <button type="submit" class="btn btn-success form-control"
                                 onclick="return confirmSave();">
                                    {{ __('登録') }}
                                </button>
                            </div>
                            <div class="col-md-2 offset-md-6">
                                <a href="{{ route('article.index') }}" class="btn btn-primary form-control">
                               {{ __('キャンセル') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>

function confirmSave(){

    return confirm("スレッドを新規に作成します。スレッドのタイトルは後から変更できません。よろしいですか？");
}

</script>
