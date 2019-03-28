@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <span class="col-md-10">スレッドリスト</span>
                        <span class="col-md-2 pull-right">
                            <a class="nav-link" href="{{ route('article.create') }}">{{ __('新規スレッド') }}</a>
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
                        <th width=20% >投稿者</th>
                        <th width=25% >日時</th>
                        <th>コメント数</th>
                    </tr></thead>
                    <tbody>
                    @forelse($threads as $th)
                    <tr>
                        <td>{{ $th->title }}</td>
                        <td>{{ $th->user->name }}</td>
                        <td>{{ date("Y/m/d H:i", strtotime($th->created_at)) }}</td>
                        <td>{{ count($th->comments) }}</td>
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
            </div>
        </div>
    </div>
</div>
@endsection
