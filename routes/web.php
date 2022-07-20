<?php

use App\Http\Controllers\User\UserRelationshipController;
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

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/dashboard', [UserRelationshipController::class, 'index'])->name('dashboard');
    Route::post('/users', [UserRelationshipController::class, 'getUsers'])->name('modal.users');
    Route::post('/relationships', [UserRelationshipController::class, 'store'])->name('relationship.store');
    Route::delete('/relationships/{relationship}', [UserRelationshipController::class, 'destroy'])->name('relationship.destroy');
});

require __DIR__.'/auth.php';
