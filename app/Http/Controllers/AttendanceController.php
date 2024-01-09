<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkTime;
use App\Models\BreakTime;
use App\Models\Status;

class AttendanceController extends Controller
{
    public function index()
    {
        // ログインしているユーザーのIDを取得
        $user_id = auth()->user()->id;

        // ログインしているユーザーに紐づくステータスレコードを取得
        $status = Status::firstOrNew(['user_id' => $user_id]);

        return view('attendance', compact('status'));
    }

    public function recordAttendance(Request $request)
    {
        $user_id = auth()->user()->id;
        $work_date = now()->toDateString();
        $status = Status::firstOrNew(['user_id' => $user_id]);

        // 当日の勤怠レコードが既に存在するか確認
        $existingRecord = WorkTime::where('user_id', $user_id)
            ->where('work_date', $work_date)
            ->first();

        if ($existingRecord) {
            // 当日の出勤情報がすでに存在する場合の処理
            // 勤務終了ボタンがクリックされた場合、最新の勤務データに勤務終了時間を記録
            if ($request->has('action') && $request->input('action') == 'work_end') {
                $latestRecord = WorkTime::where('user_id', $user_id)
                    ->latest('created_at')
                    ->first();

                if ($latestRecord) {
                    $latestRecord->update([
                        'work_end' => now()->toTimeString(),
                    ]);

                    // $status を更新
                    $status->update([
                        'work_ended' => true,
                    ]);
                }
            }

            // 休憩開始ボタンがクリックされた場合、休憩開始時間を記録
            if ($request->has('action') && $request->input('action') == 'break_start') {
                BreakTime::create([
                    'work_time_id' => $existingRecord->id,
                    'break_start' => now()->toTimeString(),
                    'break_end' => null, // 'break_end' は現時点では NULL
                ]);

                // $status を更新
                $status->update([
                    'break_started' => true,
                ]);
            }

            // 休憩終了ボタンがクリックされた場合、最新の休憩データに休憩終了時間を記録
            if ($request->has('action') && $request->input('action') == 'break_end') {
                $latestBreak = BreakTime::where('work_time_id', $existingRecord->id)
                    ->latest('created_at')
                    ->first();

                if ($latestBreak && !$latestBreak->break_end) {
                    $latestBreak->update([
                        'break_end' => now()->toTimeString(),
                    ]);

                    // $status を更新
                    $status->update([
                        'break_started' => false,
                    ]);
                }
            }
             return redirect()->route('attendance.index');

        } else {
            // 当日の勤務データが存在しない場合、レコードを作成して勤務開始時間を記録
            if ($request->has('action') && $request->input('action') == 'work_start') {
                WorkTime::create([
                    'user_id' => $user_id,
                    'work_date' => $work_date,
                    'work_start' => now()->toTimeString(),
                    // 'work_end' は現時点では NULL
                ]);

                // $status を更新
                $status->update([
                    'work_started' => true,
                ]);
            }
            return redirect()->route('attendance.index');
        }
    }
}
