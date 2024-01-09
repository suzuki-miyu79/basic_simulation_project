@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance-list.css') }}">
@endsection

@section('content')
    <div class="attendance-list__content">
        <div class="pagination_top">
            <!-- 日付別のページネーション -->
            <a
                href="{{ route('show.list', ['date' => \Carbon\Carbon::parse($defaultDate)->subDay()->format('Y-m-d')]) }}">&lt;</a>
            {{ $defaultDate }}
            <a
                href="{{ route('show.list', ['date' => \Carbon\Carbon::parse($defaultDate)->addDay()->format('Y-m-d')]) }}">&gt;</a>
        </div>
        <div class="list__table">
            <table class="list__inner">
                <tr class="list__column">
                    <th class="list__column-item column-item-name">名前</th>
                    <th class="list__column-item">勤務開始</th>
                    <th class="list__column-item">勤務終了</th>
                    <th class="list__column-item">休憩時間</th>
                    <th class="list__column-item">勤務時間</th>
                </tr>
                @foreach ($workTimes as $workTime)
                    <tr class="list__row">
                        <td class="list__row-item row-item-name">{{ $workTime->user->name }}</td>
                        <td class="list__row-item">{{ $workTime->work_start }}</td>
                        <td class="list__row-item">{{ $workTime->work_end }}</td>
                        <td class="list__row-item">
                            {{ $workTime->calculateTotalBreakHours() }}
                        </td>
                        <td class="list__row-item">
                            {{ $workTime->calculateWorkHours() }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="pagination__bottom">
            {{ $workTimes->appends(['date' => $defaultDate])->links('vendor.pagination.bootstrap-5') }}</div>
    </div>
@endsection
