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
                        <div class="col-md-12">
                            {!! nl2br(
                                mb_ereg_replace('(https?://[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,%#]+)', 
                                    '<a href="\1" target="_blank">\1</a>', $article->message), false) 
                            !!}
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <span class="offset-md-10 col-md-2 pull-right">
                        <a href={{ route('article.index') }} class="btn btn-primary">{{ __('スレッドリスト') }}</a> 
                    </span>    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
