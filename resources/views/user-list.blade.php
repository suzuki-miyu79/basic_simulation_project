@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user-list.css') }}">
@endsection

@section('content')
    <div class="user-list__content">
        <div class="user-list__heading">
            <h2>ユーザーページ</h2>
        </div>
        <div class="search">
            <form action="{{ route('users.index') }}" method="GET">
                <input type="text" name="keyword" placeholder="名前" class="search__input"
                    value="@if (isset($keyword)) {{ $keyword ?? '' }} @endif">
                <button type="submit" class="search__button">検索</button>
            </form>
        </div>
        <div class="list__table">
            <table class="list__inner">
                <tr class="list__column">
                    <th class="list__column-item column-item-id">ID</th>
                    <th class="list__column-item">名前</th>
                </tr>
                @foreach ($users as $user)
                    <tr class="list__row">
                        <td class="list__row-item row-item-id">{{ $user->id }}</td>
                        <td class="list__row-item row-item-name">{{ $user->name }}</td>
                        <td class="list__row-item row-item-button">
                            <a href="{{ route('users.attendance', $user) }}" class="list__button">一覧</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="pagination">{{ $users->appends(['keyword' => $keyword])->links('vendor.pagination.bootstrap-5') }}</div>
    </div>
@endsection
