<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceListController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// 認証済みであれば打刻画面、未承認であればログイン画面を表示
Route::get('/', function () {
    if (Auth::check()) {
        // ユーザーが認証済みの場合は 'index' アクションを呼び出す
        return app(AttendanceController::class)->index();
    } else {
        // ユーザーが未認証の場合は login ページを表示
        return view('auth.login');
    }
})->name('attendance.index');

// 勤怠一覧画面表示
Route::get('/attendance-list', [AttendanceListController::class, 'showList']);

// 勤務・休憩打刻
Route::post('/record-attendance', [AttendanceController::class, 'recordAttendance'])->name('record.attendance');

// Route::get('/attendance', function () {
//     return view('attendance');
// })->middleware(['auth', 'verified'])->name('attendance');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
