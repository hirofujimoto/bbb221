@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <span class="col-md-10">{{ sprintf("スレッド「%s」へのコメント",$comment->article->title) }} </span>
                        <span class="col-md-2">{{ $comment->user->name }}</span>
                    </div>
                </div>
                <div class="card-body">
                    @csrf
                    <div class="form-group row h-500">
                        <div class="col-md-11">
                            {!! nl2br(
                                mb_ereg_replace('(https?://[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,%#]+)', 
                                    '<a href="\1" target="_blank">\1</a>', $comment->message), false) 
                            !!}
                            @if($comment->has_image)
                                <br>
                                <p>
                                <img src="{{ Storage::url(sprintf("c%08d",$comment->id)) }}" />
                                </p>
                            @endif
                        </div>
                        <div class="col-md-1">
                            <div class="button_column">
                            @if($comment->previous())
                            <span class='prev_button'>
                                <a href={{ route('comment.show',[$comment->previous()] )}}
                                    class="btn btn-success form-controll">{{ __('▲') }} </a>
                            </span>
                            @else
                            <span class='prev_button'>
                                <a href={{ route('article.show',[$comment->article_id] )}}
                                    class="btn btn-success form-controll">{{ __('▲') }} </a>
                            </span>
                            @endif
                            @if($comment->next())
                            <span class='next_button'>
                                <a href={{ route('comment.show',[$comment->next()] )}}
                                    class="btn btn-success form-controll">{{ __('▼') }} </a>
                            </span>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form-group row">
                        <span class="col-md-2">
                            <a href={{ route('comment.create',[$comment->article_id]) }} class="btn btn-primary form-control">{{ __('コメント') }}</a>
                        </span>
                        <span class="offset-md-8 col-md-2">
                            <a href={{ route('article.index') }} class="btn btn-primary form-control">{{ __('スレッドリスト') }}</a> 
                        </span>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
