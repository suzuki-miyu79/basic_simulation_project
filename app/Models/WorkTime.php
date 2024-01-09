<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WorkTime extends Model
{
    protected $fillable = ['user_id', 'work_date', 'work_start', 'work_end'];

    // User モデルとのリレーションシップ
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // BreakTime モデルとのリレーションシップ
    public function breakTimes()
    {
        return $this->hasMany(BreakTime::class, 'work_time_id');
    }

    // 時間のフォーマットを行う共通メソッド
    public function formatTime(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }

    // 勤務時間を計算するメソッド
    public function calculateWorkTimes()
    {
        // 勤務開始と終了の時間を取得
        $start = Carbon::parse($this->work_start);
        $end = Carbon::parse($this->work_end);

        // 勤務時間を秒単位で取得
        $workSeconds = $end->diffInSeconds($start);

        return $this->formatTime($workSeconds);
    }

    // 休憩時間を差し引いた勤務時間を計算するメソッド
    public function calculateWorkHours()
    {
        // 勤務開始と終了の時間を取得
        $start = Carbon::parse($this->work_start);
        $end = Carbon::parse($this->work_end);

        // 勤務時間を秒単位で取得
        $workSeconds = $end->diffInSeconds($start);

        // 休憩時間を秒単位で取得
        $breakSeconds = $this->calculateBreakSeconds();

        // 勤務時間から休憩時間を差し引いて、時、分、秒に変換してフォーマット
        $totalSeconds = max($workSeconds - $breakSeconds, 0);
        return $this->formatTime($totalSeconds);
    }

    // 休憩時間を秒単位で計算するメソッド
    public function calculateBreakSeconds()
    {
        $breakSeconds = 0;

        foreach ($this->breakTimes as $breakTime) {
            $breakSeconds += Carbon::parse($breakTime->break_end)->diffInSeconds(Carbon::parse($breakTime->break_start));
        }

        return $breakSeconds;
    }

    // 複数の休憩データの合計休憩時間を計算するメソッド
    public function calculateTotalBreakHours()
    {
        // 各休憩データの合計休憩時間を計算
        $totalBreakSeconds = 0;

        foreach ($this->breakTimes as $breakTime) {
            $totalBreakSeconds += Carbon::parse($breakTime->break_end)->diffInSeconds(Carbon::parse($breakTime->break_start));
        }

        return $this->formatTime($totalBreakSeconds);
    }
    public function calculateMonthlyTotalTimes($year, $month)
    {
        // 年月に基づいて勤怠情報を取得
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // WorkTimeテーブルからデータを取得
        $workTimes = WorkTime::with('breakTimes')
            ->where('user_id', $this->user_id)
            ->whereBetween('work_date', [$startDate, $endDate])
            ->get();

        $totalWorkTime = 0;
        $totalBreakTime = 0;
        $totalActualWorkTime = 0;

        foreach ($workTimes as $workTime) {
            // 各日の勤務時間、休憩時間、実際の勤務時間を秒単位で計算して合計に加算
            $totalWorkTime += is_numeric($workTime->calculateWorkTimes()) ? (float) $workTime->calculateWorkTimes() : 0;
            $totalBreakTime += is_numeric($workTime->calculateTotalBreakHours()) ? (float) $workTime->calculateTotalBreakHours() : 0;
            $totalActualWorkTime += is_numeric($workTime->calculateWorkHours()) ? (float) $workTime->calculateWorkHours() : 0;
        }

        return [
            'totalWorkTime' => $this->formatTime($totalWorkTime),
            'totalBreakTime' => $this->formatTime($totalBreakTime),
            'totalActualWorkTime' => $this->formatTime($totalActualWorkTime),
        ];
    }

    // ユーザー別勤怠一覧用の時間計算メソッド
    // 勤務時間を秒単位で計算するメソッド
    public function calculateWorkTimesInSeconds()
    {
        $start = Carbon::parse($this->work_start);
        $end = Carbon::parse($this->work_end);

        return $end->diffInSeconds($start);
    }

    // 実労働時間を秒単位で計算するメソッド
    public function calculateWorkHoursInSeconds()
    {
        $start = Carbon::parse($this->work_start);
        $end = Carbon::parse($this->work_end);

        $workSeconds = $end->diffInSeconds($start);
        $breakSeconds = $this->calculateBreakSeconds();

        return max($workSeconds - $breakSeconds, 0);
    }
}