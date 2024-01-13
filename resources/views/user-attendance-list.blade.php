@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user-attendance-list.css') }}">
@endsection

@section('content')
    <div class="user-attendance-list__content">
        <div class="user-attendance-list__heading">
            <h2>{{ $user->name }}さんの勤怠表</h2>
        </div>
        <div class="year-month-selection">
            <form action="{{ route('users.attendance', $user) }}" method="get">
                @csrf
                <div class="form-row">
                    <label for="year_month">年月選択：</label>
                    <input type="month" name="year_month" id="year_month" class="form-control"
                        value="{{ $selectedYearMonth }}">
                </div>
            </form>
            <a href="{{ route('users.index') }}" class="back__button">ユーザーページへ戻る</a>
        </div>
        <div class="list__table">
            <table class="list__inner-top">
                <tr class="list__total-column">
                    <th class="list__total-column-item total-column-item-date" rowspan="2">月別合計</th>
                    <th class="list__total-column-item">勤務時間</th>
                    <th class="list__total-column-item">休憩時間</th>
                    <th class="list__total-column-item">実労働時間</th>
                </tr>
                <tr class="list__total-row">
                    <td class="list__total-row-item">{{ $monthlyTotalTimes['totalWorkTime'] }}</td>
                    <td class="list__total-row-item">{{ $monthlyTotalTimes['totalBreakTime'] }}</td>
                    <td class="list__total-row-item">{{ $monthlyTotalTimes['totalActualWorkTime'] }}</td>
                </tr>
            </table>
            <table class="list__inner-bottom">
                <tr class="list__column">
                    <th class="list__column-item column-item-date">月日</th>
                    <th class="list__column-item">勤務時間</th>
                    <th class="list__column-item">休憩時間</th>
                    <th class="list__column-item">実労働時間</th>
                </tr>
                @foreach ($attendanceData as $data)
                    <tr class="list__row">
                        <td class="list__row-item row-item-date">{{ $data['date'] }}</td>
                        <td class="list__row-item">{{ $data['work_time'] }}</td>
                        <td class="list__row-item">{{ $data['break_time'] }}</td>
                        <td class="list__row-item">{{ $data['actual_work_time'] }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <script src="{{ asset('js/attendance-list.js') }}"></script>
@endsection
