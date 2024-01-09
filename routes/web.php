<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceListController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;


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
        return redirect()->route('login');
    }
})->name('attendance.index');

// 認証済みであれば、各ページを表示する
Route::middleware('auth')->group(function () {

    // 勤怠一覧画面表示
    Route::get('/attendance-list', [AttendanceListController::class, 'showList'])->name('show.list');

    // 勤務・休憩打刻
    Route::post('/record-attendance', [AttendanceController::class, 'recordAttendance'])->name('record.attendance');

    //ユーザーページ表示
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    //ユーザーごとの勤怠一覧表示
    Route::get('/users/{user}/attendance-list', [AttendanceListController::class, 'byUserAttendance'])->name('users.attendance');
});

require __DIR__ . '/auth.php';
