<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'keyword' => 'nullable|string|max:255',
        ]);

        $keyword = $validated['keyword'] ?? null;

        $query = User::query();
        $query = User::with('profile');

        if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        $users = $query->paginate(10);

        return view('users.index', compact('users', 'keyword'));
    }

    public function show($id)
    {
        // プロフィール情報を一緒に取得
        $user = User::with('profile')->findOrFail($id);

        $isFollowing = false;

        if (auth()->check() && auth()->id() !== $user->id) {
            $isFollowing = \DB::table('follows')
                ->where('user_id', auth()->id())
                ->where('followed_user_id', $user->id)
                ->exists();
        }

        return view('users.show', compact('user', 'isFollowing'));
    }

    public function follow(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', '自分自身はフォローできません。');
        }

        auth()->user()->followings()->syncWithoutDetaching([$user->id]);
        return back()->with('message', 'フォローしました。');
    }

    public function unfollow(User $user)
    {
        auth()->user()->followings()->detach($user->id);
        return back()->with('message', 'フォローを解除しました。');
    }

    public function following_index()
    {
        $user = auth()->user(); // ログインユーザー取得

        // 自分がフォローしているユーザー一覧を取得
        $followings = $user->followings()->get(); 

        return view('users.followings', compact('followings'));
    }
}
