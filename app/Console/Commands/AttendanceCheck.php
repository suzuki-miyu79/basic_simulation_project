<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Status;
use App\Models\WorkTime;
use Carbon\Carbon;

class AttendanceCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update attendance records for all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // ユーザーのリストを取得
        $users = User::all();

        foreach ($users as $user) {
            $this->processAttendance($user);
        }

        $this->info('Attendance check completed for all users.');
    }

    private function processAttendance(User $user)
    {
        DB::transaction(function () use ($user) {
            // statusesテーブルの初期化
            Status::where('user_id', $user->id)
                ->update(['work_started' => false, 'work_ended' => false, 'break_started' => false, 'break_ended' => false]);

            // 前日の日付を取得
            $yesterday = Carbon::yesterday()->toDateString();

            // 前日の勤務レコードが存在しない場合、NULLで新しいレコードを作成
            $usersWithoutRecord = WorkTime::where('user_id', $user->id)
                ->whereDate('work_date', $yesterday)
                ->doesntExist();

            if ($usersWithoutRecord) {
                $newRecord = new WorkTime();
                $newRecord->user_id = $user->id;
                $newRecord->work_date = $yesterday;
                $newRecord->work_start = null;
                $newRecord->work_end = null;
                $newRecord->save();
            }
        });
    }
}