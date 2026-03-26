<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\PostTemplate;


class ScheduleController extends Controller
{

        protected $fillable = [
        'title',
        'start_time',
        'gunre',
        'body',
        'user_id',
        
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Schedule::all()->map(function ($schedule) {
            return [
                'title' => $schedule->title,         // 表示タイトル
                'start' => $schedule->start_time,    // ISO8601形式である必要あり
                'url'   => route('schedules.show', $schedule->id), // イベントクリックで詳細ページに飛ばす（任意）
            ];
        });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $templates = PostTemplate::where('user_id', auth()->id())->get();
        return view('schedules.create',  compact('templates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $isUnscheduled = $request->boolean('is_unscheduled');

        $inputs = $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required|date',
            'genre' => 'required|string',
            'body' => 'nullable|max:1000',
        ]);

        $post = new Schedule();

        $post->title = $request->title;

        if ($isUnscheduled) {
            // 日付だけ使って時間は00:00にする
            $date = \Carbon\Carbon::parse($request->start_time)->format('Y-m-d');
            $post->start_time = $date . ' 00:00:00';
        } else {
            $post->start_time = $request->start_time;
        }

        $post->is_unscheduled = $isUnscheduled;
        $post->genre = $request->genre;
        $post->body = $request->body;
        $post->user_id = auth()->user()->id;

        $post->save();

        return redirect()->route('post.create')->with([
            'message' => '投稿を作成しました',
            'type' => 'success',
        ]);
    }
    /**
     * Display the specified resource.
     */

    public function showByDate($date)
    {
        $user = auth()->user()?->loadMissing('followings');

        $followIds = optional($user?->followings)->pluck('id') ?? collect();

        $schedules = Schedule::with('user')
            ->whereDate('start_time', $date)
            ->where(function ($query) use ($followIds) {
                $query->where('user_id', auth()->id())
                    ->orWhereIn('user_id', $followIds);
            })
            ->orderByRaw('is_unscheduled ASC') // 通常→ゲリラ
            ->orderBy('start_time')           // 時間順
            ->get();

        return view('schedules.by-date', compact('schedules', 'date'));
    }

    public function show(Schedule $post)
    {
        return view('schedules.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $post)
    {
        return view('schedules.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $post)
    {
        $inputs=$request->validate([
            'title'=>'required|string|max:255',
            'start_time'=>'required|date',
            'genre'=>'required|string',
            'body'=>'nullable|max:1000',
        ]);

        // モデルを更新
        $post->title = $inputs['title'];
        $post->start_time = $inputs['start_time'];
        $post->genre = $inputs['genre'];
        $post->body = $inputs['body'] ?? null;
        $post->save();

        return redirect()->route('post.show', $post->id)
                     ->with( 
                        ['message' => '予定を更新しました',
                        'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $post)
    {
        $post->delete();
        return redirect()->route('dashboard')->with('message', '投稿を削除しました');
    }


    //配信がかぶっている日の色を指定できないので下記に修正
    public function api()
    {
        $userId = Auth::id();

        // 自分の配信日を取得
        $mySchedules = Schedule::selectRaw('DATE(start_time) as date')
            ->where('user_id', $userId)
            ->groupBy('date')
            ->pluck('date')
            ->toArray();

        // フォローしているユーザーの配信日を取得
        $followIds = auth()->user()->followings->pluck('id');

        $otherSchedules = Schedule::selectRaw('DATE(start_time) as date')
            ->whereIn('user_id', $followIds)
            ->groupBy('date')
            ->pluck('date')
            ->toArray();

        // 日付をマージしてユニーク化
        $allDates = collect($mySchedules)
            ->merge($otherSchedules)
            ->unique();

        $events = [];

        foreach ($allDates as $date) {
            $isMine = in_array($date, $mySchedules);
            $isFollow = in_array($date, $otherSchedules);

            $backgroundColor = match (true) {
                $isMine && $isFollow => '#FFB266', // 両方ある：グレー
                $isMine => '#7DFF76',              // 自分の配信だけ：緑
                $isFollow => '#76CDFF',            // フォロー中だけ：水色
                default => '#FFFFFF',
            };

            $events[] = [
                'start' => $date,
                'display' => 'background',
                'backgroundColor' => $backgroundColor,
            ];
        }

        return response()->json($events);
    }

}