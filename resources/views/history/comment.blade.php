@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <span class="col-md-10">{{ sprintf("スレッド「%s」へのコメント",$history->article->title) }} の履歴</span>
                        <span class="col-md-2">{{ $history->comment->user->name }}</span>
                    </div>
                </div>
                <div class="card-body">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-11">
                            {!! nl2br(
                                mb_ereg_replace('(https?://[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,%#]+)', 
                                    '<a href="\1" target="_blank">\1</a>', $history->message), false) 
                            !!}
                            @if($history->image_tag != '')
                                <br>
                                <p>
                                <img src="{{ Storage::url(sprintf("a%08d%s",$history->comment_id, $history->image_tag)) }}" />
                                </p>
                            @endif
                        </div>
                        <div class="col-md-1">
                            <div class="button_column">
                                @if($depth > 1)
                                <span class='prev_button'>
                                    <a href={{ route('history.comment',[$history->comment_id, $depth-1] )}}
                                        class="btn btn-warning form-control">{{ __('▲') }} </a>
                                </span>
                                @endif
                                @if($depth < $count)
                                <span class='next_button'>
                                    <a href={{ route('history.comment',[$history->comment_id, $depth+1] )}}
                                        class="btn btn-warning form-control">{{ __('▼') }} </a>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form-group row">
                        <span class="col-md-2">
                            <a href={{ route('comment.show',[$history->comment_id] )}} class="btn btn-warning form-control">{{ __('スレッドに戻る') }} </a>
                        </span>
                        <span class="col-md-8">
                        </span>
                        <span class="col-md-2">
                            <a href={{ route('article.index') }} class="btn btn-primary form-control">{{ __('スレッドリスト') }}</a> 
                        </span>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
