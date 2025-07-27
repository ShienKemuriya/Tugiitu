<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Route::resource('post', ScheduleController::class);
    Route::get('/api/schedules', [ScheduleController::class, 'api'])->name('schedules.api');
    Route::get('/schedules/date/{date}', [ScheduleController::class, 'showByDate'])->name('schedules.byDate');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/show/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/followings/',[UserController::class, 'following_index'])->name('users.followings');

    Route::post('/users/{user}/follow', [UserController::class, 'follow'])->name('users.follow');
    Route::delete('/users/{user}/unfollow', [UserController::class, 'unfollow'])->name('users.unfollow');

    //プロフィールのルーティング
    Route::get('/profile',[ProfileController::class, 'show'])->name('profiles.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profiles.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profiles.update');
});

require __DIR__.'/auth.php';
