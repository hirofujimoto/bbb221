@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <span class="col-md-10">{{ $article->title }} </span>
                        <span class="col-md-2">{{ $article->user->name }}</span>
                    </div>
                </div>
                <div class="card-body">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-11">
                            {!! nl2br(
                                mb_ereg_replace('(https?://[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,%#]+)', 
                                    '<a href="\1" target="_blank">\1</a>', $article->message), false) 
                            !!}
                            @if($article->has_image)
                                <br>
                                <p>
                                <img src="{{ Storage::url(sprintf("a%08d",$article->id)) }}" />
                                </p>
                            @endif
                        </div>
                        <div class="col-md-1">
                            <div class="button_column">
                            @if(count($article->comments))
                            <span class="next_button">
                                <a href={{ route('comment.show',[$article->comments()->first()->id] )}}
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
                            <a href={{ route('comment.create',[$article->id]) }} 
                                class="btn btn-primary form-control">{{ __('コメントする') }}</a>
                        </span>
                        @if ($article->user_id == \Auth::user()->id)
                        <span class="offset-md-6 col-md-2">
                            <a href={{ route('article.edit',[$article->id])}}, class="btn btn-danger form-control">{{ __('編集') }}</a>
                        </span>
                        <span class="col-md-2">
                            <a href={{ route('article.index') }} class="btn btn-primary form-control">{{ __('スレッドリスト') }}</a> 
                        </span>    

                        @else
                        <span class="offset-md-8 col-md-2">
                            <a href={{ route('article.index') }} class="btn btn-primary form-control">{{ __('スレッドリスト') }}</a> 
                        </span>    
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
