@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')
    <div class="attendance__content">
        <div class="attendance__heading">
            <p>
                {{ Auth::user()->name }}さんお疲れ様です！
            </p>
        </div>
        <form id="attendanceForm" action="{{ route('record.attendance') }}" method="POST">
            @csrf
            <div class="group__working">
                <button type="submit" name="action" value="work_start"
                    class="attendance__button attendance__button-top attendance__button-left" id="startWork"
                    onclick="startWork()" @if ($status->work_started || $status->work_ended || $status->break_started || $status->break_ended) disabled @endif>勤務開始</button>
                <button type="submit" name="action" value="work_end"
                    class="attendance__button attendance__button-top attendance__button-right" id="endWork"
                    onclick="endWork()" @if (
                        (!$status->work_started && !$status->work_ended && !$status->break_started && !$status->break_ended) ||
                            $status->break_started ||
                            $status->work_ended) disabled @endif>勤務終了</button>
            </div>
            <div class="group__break">
                <button type="submit" name="action" value="break_start"
                    class="attendance__button attendance__button-buttom attendance__button-left" id="startBreak"
                    onclick="startBreak()" @if (
                        (!$status->work_started && !$status->work_ended && !$status->break_started && !$status->break_ended) ||
                            $status->break_started ||
                            $status->work_ended) disabled @endif>休憩開始</button>
                <button type="submit" name="action" value="break_end"
                    class="attendance__button attendance__button-buttom attendance__button-right" id="endBreak"
                    onclick="endBreak()" @if (!$status->break_started) disabled @endif>休憩終了</button>
            </div>
        </form>
    </div>
@endsection
