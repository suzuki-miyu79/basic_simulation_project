<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // ヘッダーリンクからのアクセス時にセッションを削除
        if ($request->has('clear_search')) {
            session()->forget('keyword');
        }

        $keyword = $request->input('keyword');

        // フォームを送信時にセッションに保存
        if ($request->has('keyword')) {
            $keyword = $request->input('keyword');
            session()->put('keyword', $keyword);
        }

        // セッションからキーワードを取得
        $keyword = session('keyword');

        // キーワードに部分一致するユーザーをページネーションで取得
        $users = User::where('name', 'like', "%$keyword%")
            ->paginate(5);

        return view('user-list', compact('users', 'keyword'));
    }
}
