// ページ読み込み時にボタンの状態を設定
document.addEventListener("DOMContentLoaded", function () {
    updateButtonStatus();
});

// ボタンの状態を更新する関数
function updateButtonStatus() {
    // セッションデータを取得
    const buttonStatus = JSON.parse('{!! json_encode(Session::get("button_status", ["work_start" => "active", "work_end" => "inactive", "break_start" => "inactive", "break_end" => "inactive"])) !!}');

    document.getElementById('work_start_btn').disabled = buttonStatus['work_start'] !== 'active';
    document.getElementById('work_end_btn').disabled = buttonStatus['work_end'] !== 'active';
    document.getElementById('break_start_btn').disabled = buttonStatus['break_start'] !== 'active';
    document.getElementById('break_end_btn').disabled = buttonStatus['break_end'] !== 'active';
}