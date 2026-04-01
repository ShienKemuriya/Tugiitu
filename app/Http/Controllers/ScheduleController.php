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


    public function api(Request $request)
    {
        $userId = Auth::id();
        $targetUserId = $request->query('user_id');
        $genre = $request->query('genre');
        $date = $request->query('date');

        $query = Schedule::with(['user.profile'])
            ->whereNotNull('start_time')
            ->when($genre, fn($q) => $q->where('genre', $genre))
            ->when($date, fn($q) => $q->whereDate('start_time', $date));

        if ($targetUserId) {
            $schedules = (clone $query)->where('user_id', $targetUserId)
                ->get()
                ->groupBy(fn($s) => $s->start_time?->format('Y-m-d'));

            $events = [];
            $backgroundColor = ($targetUserId == $userId) ? '#7DFF76' : '#76CDFF';

            foreach ($schedules as $dateStr => $daySchedules) {
                if (!$dateStr) continue;
                $users = $daySchedules->map(fn($s) => [
                    'id' => $s->user_id,
                    'name' => $s->user->name,
                    'icon' => $s->user->profile?->icon ? asset('storage/' . $s->user->profile->icon) : null,
                ])->unique('id')->values();

                $events[] = [
                    'start' => $dateStr,
                    'display' => 'background',
                    'backgroundColor' => $backgroundColor,
                    'extendedProps' => [
                        'users' => $users,
                        'total_users' => $users->count(),
                    ]
                ];
            }
            return response()->json($events);
        }

        if (!Auth::check()) {
            $schedules = (clone $query)->get()
                ->groupBy(fn($s) => $s->start_time?->format('Y-m-d'));

            $events = [];
            foreach ($schedules as $dateStr => $daySchedules) {
                if (!$dateStr) continue;
                $users = $daySchedules->map(fn($s) => [
                    'id' => $s->user_id,
                    'name' => $s->user->name,
                    'icon' => $s->user->profile?->icon ? asset('storage/' . $s->user->profile->icon) : null,
                ])->unique('id')->values();

                $events[] = [
                    'start' => $dateStr,
                    'display' => 'background',
                    'backgroundColor' => '#76CDFF',
                    'extendedProps' => [
                        'users' => $users,
                        'total_users' => $users->count(),
                    ]
                ];
            }
            return response()->json($events);
        }

        $followIds = auth()->user()->followings->pluck('id')->push($userId);

        $schedules = (clone $query)->whereIn('user_id', $followIds)
            ->get()
            ->groupBy(fn($s) => $s->start_time?->format('Y-m-d'));

        $events = [];
        foreach ($schedules as $dateStr => $daySchedules) {
            if (!$dateStr) continue;
            $users = $daySchedules->map(fn($s) => [
                'id' => $s->user_id,
                'name' => $s->user->name,
                'icon' => $s->user->profile?->icon ? asset('storage/' . $s->user->profile->icon) : null,
                'is_me' => ($s->user_id == $userId),
            ])->unique('id')->values();

            $isMine = $users->contains('is_me', true);
            $isFollow = $users->where('is_me', false)->count() > 0;

            $backgroundColor = match (true) {
                $isMine && $isFollow => '#FFB266',
                $isMine => '#7DFF76',
                $isFollow => '#76CDFF',
                default => '#76CDFF'
            };

            $events[] = [
                'start' => $dateStr,
                'display' => 'background',
                'backgroundColor' => $backgroundColor,
                'extendedProps' => [
                    'users' => $users,
                    'total_users' => $users->count(),
                ]
            ];
        }

        return response()->json($events);
    }

}