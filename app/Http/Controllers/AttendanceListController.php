<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkTime;
use App\Models\BreakTime;

class AttendanceListController extends Controller
{
    public function showList()
    {
        $workTimes = WorkTime::all();
        $breakTimes = BreakTime::all();
        return view('attendance-list', compact('workTimes', 'breakTimes'));
    }
}
