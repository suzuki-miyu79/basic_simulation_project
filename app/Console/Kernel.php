<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\WorkTime;
use App\Models\User;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // 毎日0時に実行されるジョブ
        $schedule->call(function () {
            $this->performDailyTasks();
        })->dailyAt('00:00');
    }

    private function performDailyTasks()
    {
        // 勤務中に日を跨いだユーザーの出勤操作切り替え
        $this->performPreviousDayClosingForEligibleUsers();
    }

    private function performPreviousDayClosingForEligibleUsers()
    {
        // 前日の勤務開始時間が打刻済み且つ、勤務終了時間が未打刻の場合
        User::whereHas('WorkTime', function ($query) {
            $query->whereNotNull('work_start')
                ->where(function ($subquery) {
                    $subquery->whereNull('work_end')
                        ->orWhereDate('work_date', '<', now()->subDay()->toDateString());
                });
        })->get()->each(function ($user) {
            $this->performPreviousDayClosing($user->id); // 前日の勤務終了処理を実行
        });
    }

    private function performPreviousDayClosing($userId)
    {
        $latestAttendance = $this->getLatestAttendance($userId);

        // 勤務終了時間が未打刻の場合
        if ($latestAttendance && is_null($latestAttendance->work_end)) {
            // 前日の23:59を勤務終了時間として打刻
            $latestAttendance->update([
                'work_end' => Carbon::yesterday()->endOfDay(),
            ]);

            // 本日の0:00を勤務開始時間として打刻
            $this->createWorkTimeEntry($userId);
        } elseif ($this->isYesterdayWorkRecordMissing($userId)) {
            // 前日のwork_dateが記録されたレコードが存在しない場合、前日のWorkTimeレコードを作成
            $this->createYesterdayWorkTimeEntry($userId);
        }
    }

    private function getLatestAttendance($userId)
    {
        // ユーザーの最新の出勤レコードを取得
        return WorkTime::where('user_id', $userId)
            ->latest('work_date')
            ->first();
    }

    private function isYesterdayWorkRecordMissing($userId)
    {
        // 前日のwork_dateが記録されたレコードが存在しない場合はtrueを返す
        return !WorkTime::where('user_id', $userId)
            ->where('work_date', now()->subDay()->toDateString())
            ->exists();
    }

    private function createWorkTimeEntry($userId)
    {
        // 新しいレコードを作成し、本日の0:00を勤務開始時間として打刻
        WorkTime::create([
            'user_id' => $userId,
            'work_date' => now()->toDateString(),
            'work_start' => now()->startOfDay(),
            'work_end' => null,
        ]);
    }

    private function createYesterdayWorkTimeEntry($userId)
    {
        // 前日の勤務実績NULLのレコードを作成
        WorkTime::create([
            'user_id' => $userId,
            'work_date' => Carbon::yesterday()->toDateString(),
            'work_start' => null,
            'work_end' => null,
        ]);
    }
}