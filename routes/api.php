<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleController ;

Route::get('/schedules', [ScheduleController::class, 'index']);

Route::apiResource('Schedule', ScheduleController ::class);
