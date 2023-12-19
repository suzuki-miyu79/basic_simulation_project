@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance-list.css') }}">
@endsection

@section('content')
    <div class="attendance-list__content">
        <div class="pagination_top"></div>
        <div class="list__table">
            <table class="list__inner">
                <tr class="list__column">
                    <th class="list__column-item">名前</th>
                    <th class="list__column-item">勤務開始</th>
                    <th class="list__column-item">勤務終了</th>
                    <th class="list__column-item">休憩時間</th>
                    <th class="list__column-item">勤務時間</th>
                </tr>
                <tr class="list__row">
                    @foreach ($workTimes as $workTime)
                        <td class="list__row-item">{{ $workTime->user->name }}</td>
                        <td class="list__row-item">{{ $workTime->work_start }}</td>
                        <td class="list__row-item">{{ $workTime->work_end }}</td>
                        <td class="list__row-item">{{ $workTime->totalBreakDuration }}</td>
                        <td class="list__row-item">{{ $workTime->work_duration }}</td>
                    @endforeach
                </tr>
            </table>
        </div>
        <div class="pagination__bottom"></div>
    </div>
@endsection
