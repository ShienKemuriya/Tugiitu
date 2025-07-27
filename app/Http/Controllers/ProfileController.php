<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $profile = auth()->user()->profile ?? new \App\Models\Profile();
        return view("profiles.show", compact('profile', 'user'));
    }
    public function edit()
    {
        $profile = auth()->user()->profile ?? new \App\Models\Profile();
        return view('profiles.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio' => 'nullable|string|max:1000',
        ]);

    // アップロード処理

    if ($request->hasFile('icon')) {
        $path = $request->file('icon')->store('icons', 'public');
        $data['icon'] = basename($path); // ファイル名だけ保存
    }

    $data['user_id'] = auth()->id();
    auth()->user()->profile()->updateOrCreate(['user_id' => auth()->id()], $data);

    return redirect()->route('profiles.edit')->with('success', 'プロフィールを更新しました');
    }
}
