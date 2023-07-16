<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\visionController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\AdminApiController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/new_video', [adminController::class, 'addVideo'])->name('admin.addVideo');
    Route::post('/new_video', [adminController::class, 'saveVideo'])->name('admin.saveVideo');
    Route::get('/editVideo/{id}', [adminController::class, 'editVideo'])->name('admin.editVideo');
    Route::get('/getvideoresults/{id}', [adminController::class, 'videoResults'])->name('admin.VideoResults');

    
    //API
    Route::get('/getvideolist', [AdminApiController::class, 'getVideoList']);
    Route::post('/getvideoresults', [AdminApiController::class, 'getVideoResults']);
    Route::post('/getusersvideo', [AdminApiController::class, 'getusersvideo']);
    Route::post('/getprojectsvideo', [AdminApiController::class, 'getprojectsvideo']);
    
    
});

Route::get('/vision/{uuid}', [visionController::class, 'show'])->name('vision.show');
Route::post('/vision', [visionController::class, 'saveDetection'])->name('vision.save');

require __DIR__.'/auth.php';
