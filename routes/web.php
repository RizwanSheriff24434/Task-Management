<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TaskController;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});



//Dashboard route to display tasks
Route::get('/dashboard', function () {
    $tasks = Task::where('user_id', Auth::id())->latest()->get();

    return Inertia::render('Dashboard', [
        'tasks' => $tasks
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');


// Task management routes
Route::post('/tasks', [TaskController::class, 'store'])->middleware('auth');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
Route::put('/tasks/{task}', [TaskController::class, 'update'])->middleware('auth');
Route::get('/tasks/{id}', [TaskController::class, 'show']);

// Profile management routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// API route to fetch a random quote
Route::get('/api/quote', function () {
    $response = Http::get('https://zenquotes.io/api/random');
    return $response->json();
});

require __DIR__.'/auth.php';
