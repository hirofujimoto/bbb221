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
                                <img src="{{ Storage::url(sprintf('c%08d',$comment->id)) }}" />
                                </p>
                            @endif
                        </div>
                        <div class="col-md-1">
                            <div class="button_column">
                            @if($neighbours['previous'])
                            <span class='prev_button'>
                                <a href="{{ route('comment.show',[$neighbours['previous']] )}}"
                                    class="btn btn-success form-control">{{ __('▲') }} </a>
                            </span>
                            @else
                            <span class='prev_button'>
                                <a href="{{ route('article.show',[$comment->article_id] )}}"
                                    class="btn btn-success form-control">{{ __('▲') }} </a>
                            </span>
                            @endif
                            @if($neighbours['next'])
                            <span class='next_button'>
                                <a href="{{ route('comment.show',[$neighbours['next']] )}}"
                                    class="btn btn-success form-control">{{ __('▼') }} </a>
                            </span>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form-group row">
                        <span class="col-md-2">
                            <a href="{{ route('comment.create',[$comment->article_id, $comment->id]) }}"
                                 class="btn btn-success form-control">{{ __('コメントする') }}</a>
                        </span>
                        <span class="col-md-2">
                            @if (count($comment->histories))
                            <!--
                                <a href="{{ route('history.comment',[$comment->id]) }}", class="btn btn-warning form-control">{{ __('履歴') }}</a>
                            -->
                            @endif
                        </span>
                        <span class="col-md-2">
                        </span>
                        <span class="col-md-2">
                            @if ($comment->user_id == \Auth::user()->id)
                                <a href="{{ route('comment.edit',[$comment->id])}}", class="btn btn-danger form-control">{{ __('編集') }}</a>
                            @endif
                        </span>
                        <span class="col-md-2">
                            <a href="{{ route('article.tree',[$comment->article_id]) }}" class="btn btn-primary form-control">{{ __('スレッドツリー') }}</a> 
                        </span>
                        <span class="col-md-2">
                            <a href="{{ route('article.index') }}" class="btn btn-primary form-control">{{ __('スレッドリスト') }}</a> 
                        </span>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
