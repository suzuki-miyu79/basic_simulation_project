<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkTime;
use App\Models\User;
use Carbon\Carbon;

class AttendanceListController extends Controller
{
    public function showList(Request $request)
    {
        // リクエストから日付を取得
        $date = $request->input('date');

        // 指定日の勤務時間のデータ取得（デフォルト：今日）
        $defaultDate = $request->input('date', Carbon::today()->format('Y-m-d'));

        // クエリの生成
        $query = WorkTime::with('breakTimes');

        // 日付が指定されていれば、その日のデータのみ取得
        if ($date) {
            $query->where('work_date', $date);
        } else {
            // 指定がなければデフォルトの日付でクエリを絞る
            $query->where('work_date', $defaultDate);
        }

        // ページネーション
        $workTimes = $query->paginate(5);

        // 日付の一覧を取得
        $uniqueDates = WorkTime::distinct()->pluck('work_date');

        // ビューにデータを渡す
        return view('attendance-list', compact('workTimes', 'uniqueDates', 'defaultDate'));
    }

    public function byUserAttendance(Request $request, User $user)
    {
        // ドロップダウンで選択された年月を取得
        $selectedYearMonth = $request->input('year_month', now()->format('Y-m'));

        // 年月に基づいて勤怠情報を取得
        $startDate = Carbon::parse($selectedYearMonth)->startOfMonth();
        $endDate = Carbon::parse($selectedYearMonth)->endOfMonth();

        // WorkTimeテーブルからデータを取得
        $workTimes = WorkTime::with('breakTimes')
            ->where('user_id', $user->id)
            ->whereBetween('work_date', [$startDate, $endDate])
            ->get();

        // 日次の勤務時間を格納する配列を初期化
        $attendanceData = [];
        $totalWorkTime = 0;
        $totalBreakTime = 0;
        $totalActualWorkTime = 0;

        foreach ($workTimes as $workTime) {
            // 勤怠データを配列に追加
            $attendanceData[] = [
                'date' => $workTime->work_date,
                'work_time' => $workTime->calculateWorkTimes(),
                'break_time' => $workTime->calculateTotalBreakHours(),
                'actual_work_time' => $workTime->calculateWorkHours(),
            ];

            // 合計勤務時間、休憩時間、実際の勤務時間を計算して累積
            $totalWorkTime += is_numeric($workTime->calculateWorkTimes()) ? (float) $workTime->calculateWorkTimes() : 0;
            $totalBreakTime += is_numeric($workTime->calculateTotalBreakHours()) ? (float) $workTime->calculateTotalBreakHours() : 0;
            $totalActualWorkTime += is_numeric($workTime->calculateWorkHours()) ? (float) $workTime->calculateWorkHours() : 0;
        }

        // 月次の合計時間を格納する配列を初期化
        $monthlyTotalTimes = [
            'totalWorkTime' => 0,
            'totalBreakTime' => 0,
            'totalActualWorkTime' => 0,
        ];

        foreach ($workTimes as $workTime) {
            // 月次の合計勤務時間、休憩時間、実際の勤務時間を秒単位で累積
            $monthlyTotalTimes['totalWorkTime'] += $workTime->calculateWorkTimesInSeconds();
            $monthlyTotalTimes['totalBreakTime'] += $workTime->calculateBreakSeconds();
            $monthlyTotalTimes['totalActualWorkTime'] += $workTime->calculateWorkHoursInSeconds();
        }

        // 合計時間をフォーマット
        $monthlyTotalTimes = [
            'totalWorkTime' => (new WorkTime())->formatTime($monthlyTotalTimes['totalWorkTime']),
            'totalBreakTime' => (new WorkTime())->formatTime($monthlyTotalTimes['totalBreakTime']),
            'totalActualWorkTime' => (new WorkTime())->formatTime($monthlyTotalTimes['totalActualWorkTime']),
        ];

        return view('user-attendance-list', compact('user', 'attendanceData', 'selectedYearMonth', 'monthlyTotalTimes', 'workTimes'));
    }
}
