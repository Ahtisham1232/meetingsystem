<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MeetingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // $e = Event::get();
    // dd($e);
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware(['auth'])->group(function () {
    Route::get('/meetings', [MeetingsController::class, 'index'])->name('meetings.index');
    Route::get('/meetings/create', [MeetingsController::class, 'create'])->name('meetings.create');
    Route::put('/meetings/{meeting}', [MeetingsController::class, 'update'])->name('meetings.update');
    Route::get('/meetings/{meeting}/edit', [MeetingsController::class, 'edit'])->name('meetings.edit');
    Route::delete('/meetings/{meeting}', [MeetingsController::class, 'destroy'])->name('meetings.destroy');
    Route::post('/meetings', [MeetingsController::class, 'store'])->name('meetings.store');
});

