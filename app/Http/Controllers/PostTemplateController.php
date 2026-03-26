<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostTemplate;

class PostTemplateController extends Controller
{
    public function store(Request $request)
    {
        PostTemplate::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'body' => $request->body,
            'genre' => $request->genre,
        ]);

        return back();
    }
    public function index()
    {
        $templates = PostTemplate::where('user_id', auth()->id())->get();
        return view('templates.index', compact('templates'));
    }

    public function destroy($id)
    {
        $template = PostTemplate::where('user_id', auth()->id())->findOrFail($id);
        $template->delete();

        return back();
    }

    public function edit($id)
    {
        $template = PostTemplate::where('user_id', auth()->id())->findOrFail($id);
        return view('templates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $template = PostTemplate::where('user_id', auth()->id())->findOrFail($id);

        $template->update([
            'title' => $request->title,
            'body' => $request->body,
            'genre' => $request->genre,
        ]);

        return redirect()->route('templates.index')->with('message', 'テンプレートを更新しました');
    }


}