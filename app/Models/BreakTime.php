<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkTime;
use Carbon\Carbon;

class BreakTime extends Model
{
    protected $fillable = ['work_time_id', 'break_start', 'break_end'];

    public function workTime()
    {
        return $this->belongsTo(WorkTime::class);
    }
}
