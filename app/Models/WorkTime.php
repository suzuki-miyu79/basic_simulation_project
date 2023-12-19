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

    // 勤務時間のアクセッサメソッド
    public function getWorkDurationAttribute()
    {
        $start = new Carbon($this->work_start);
        $end = new Carbon($this->work_end);

        // 差分を分単位で計算
        $duration = $end->diffInMinutes($start);

        return $duration;
    }

    // 休憩時間の合計を計算するアクセッサメソッド
    public function getTotalBreakDurationAttribute()
    {
        $totalBreakDuration = 0;

        // 関連する休憩時間を取得
        $breakTimes = $this->breakTimes;

        // 休憩時間を合計する
        foreach ($breakTimes as $breakTime) {
            $breakStart = new Carbon($breakTime->break_start);
            $breakEnd = new Carbon($breakTime->break_end);

            // 差分を分単位で計算し、合計に加算
            $totalBreakDuration += $breakEnd->diffInMinutes($breakStart);
        }

        return $totalBreakDuration;
    }
}

