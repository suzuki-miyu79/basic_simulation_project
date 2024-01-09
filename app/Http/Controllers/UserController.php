<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        // キーワードに部分一致するユーザーをページネーションで取得
        $users = User::where('name', 'like', "%$keyword%")
            ->paginate(5);

        return view('user-list', compact('users', 'keyword'));
    }
}
