@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <span class="col-md-10">スレッドリスト（最近更新された順）</span>
                        <span class="col-md-2 pull-right">
                            <a href="{{ route('article.create') }}" class="btn btn-success">{{ __('新規スレッド作成') }}</a>
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table width=100%>
                    <thead><tr>
                        <th width=40% >タイトル</th>
                        <th width=15% >作成者</th>
                        <th width=25% >作成日時</th>
                        <th>未読/総数</th>
                        <th width=10%></th>
                    </tr></thead>
                    <tbody>
                    @forelse($threads as $th)
                    <tr>
                        <td><a href="{{ route('article.tree',[$th->id ]) }}">{{ $th->title }}</td>
                        <td>{{ $th->user->name }}</td>
                        <td>{{ date("Y/m/d H:i", strtotime($th->created_at)) }}</td>
                        <td>{{ $th->readings() }}</td>
                        <td><a href="{{ route('article.lastread',[$th->id ]) }}" >最終閲覧</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan=4>
                            スレッドがありません。
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <form method="POST" action="{{ route('article.squeeze') }}" >
                    @csrf
                    <div class="row">
                        <span class="col-md-7">
                            @if(!empty($threads))
                                {{ $threads->onEachSide(5)->links() }}
                            @endif
                        </span>
                        <span class="col-md-3">
                            <input id="needle" type="search" 
                                class="my-search form-control" name="needle" value="{{ Session::get('needle','') }}">
                        </span>
                        <span class="col-md-1">
                            <button type="submit" name="squeeze" value="squeeze" class="btn btn-primary form-control">{{ __('検索') }}</button>
                        </span>
                        <span class="col-md-1">
                            <button type="submit" name="release" value="release" class="btn btn-warning form-control">{{ __('解除') }}</button>
                        </span>       
                    </div>
                    </form>
               </div>
            </div>
        </div>
    </div>
</div>
@endsection
