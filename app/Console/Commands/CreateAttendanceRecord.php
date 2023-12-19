<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AttendanceController;

class CreateAttendanceRecord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:create-record';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create attendance record for the user if it does not exist.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $attendanceController = new AttendanceController;
        $attendanceController->createAttendanceRecordForUser();
        $this->info('Attendance record created successfully.');
    }
}
