@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ sprintf("スレッド「%s」へのコメント編集", $comment->article->title) }}</div>
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
                    <form method="POST" action="{{ route('comment.update') }}" enctype="multipart/form-data" >
                        @csrf

                        <div class="form-group row">
                            <label for="message" class="col-md-1 col-form-label text-md-right">{{ __('本文') }}</label>
                            <div class="col-md-10">
                                <input id="comment_id" type="hidden" name="comment_id" value={{ $comment->id }} >
                                <textarea id="message" class="form-control" name="message" rows="20" >{{ $comment->message }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                        <label for="imagefile" class="col-md-1 col-form-label text-md-right">{{ __('画像') }}</label>
                        @if($comment->has_image)
                            <label class="col-md-2 col-form-label">
                                <input type="radio" name="image" value="remain" checked>{{ __('　変更なし') }}</label>
                            <label class="col-md-2 col-form-label">
                                <input type="radio" name="image" value="delete">{{ __('　画像削除') }}</label>
                            <label class="col-md-2 col-form-label">
                                <input type="radio" name="image" value="change">{{ __('　画像差替 ▼') }}</label>
                        @else
                            <label class="col-md-2 col-form-label">
                                <input type="radio" name="image" value="remain" checked>{{ __('　変更なし') }}</label>
                            <label class="col-md-2 col-form-label">
                                <input type="radio" name="image" value="add">{{ __('　画像追加 ▼') }}</label>
                        @endif
                        </div>
                        <div class="form-group row" id="selectfile">
                            <div class="offset-md-1 col-md-6">
                                <input id="imagefile" type="file" class="form-control" name="imagefile">
                            </div>
                            <div class="col-md-4">
                                {{ __('画像はjpg，png。サイズは 縦横1200px までです。') }}
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-2 offset-md-1">
                                <button type="submit" class="btn btn-primary form-control">
                                    {{ __('登録') }}
                                </button>
                            </div>
                            <div class="col-md-2 offset-md-6">
                                <a href={{ route("comment.show",[$comment->id]) }} class="btn btn-primary form-control">
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

<script>

$(function(){
    $('input[name="image"]:radio').change( function() {
        var select = $(this).val();
        if(select == 'remain' || select == 'delete'){
            $("#selectfile").hide();
        }else{
            $("#selectfile").show();
        }
    });
});

$("#selectfile").hide();

</script>
@endsection
