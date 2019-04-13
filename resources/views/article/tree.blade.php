@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <span class="col-md-10">{{ sprintf("「%s」のスレッドツリー", $article->title) }} </span>
                        <span class="col-md-2"></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <span class="col-md-10">
                            <a href={{ route('article.show',[$article->id]) }} >
                            {{ sprintf("%s （%s）",$article->title, $article->user->name ) }}
                            </a>
                        </span>
                        <span class="col-md-2">
                            {{ date("Y/m/d H:i", strtotime($article->created_at)) }}
                        </span>
                    </div>
                    @forelse($tree as $b)
                        <div class="row">
                            <span class="col-md-10">
                                <a href={{ route('comment.show',[$b['id']] ) }} >
                                {{ sprintf(str_repeat("　",$b['depth'])."→ %s （%s）",$b['head'], $b['user'] ) }}
                                </a>
                            </span>
                            <span class="col-md-2">
                                {{ date("Y/m/d H:i", strtotime($b['date'])) }}
                            </span>
                        </div>
                    @empty
                        <!-- no display -->
                    @endforelse
                </div>
                <div class="card-footer">
                    <div class="form-group row">
                        <span class="col-md-10">
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
