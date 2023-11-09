<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordlessAuthenticationController;

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

Route::view('/', 'welcome');

Route::view('/login', 'auth.login');
Route::post('/login', [PasswordlessAuthenticationController::class, 'sendLink'])->middleware('throttle:3,1')->name('login');
Route::get('/login/{user}', [PasswordlessAuthenticationController::class, 'authenticateUser'])->name('passwordless.authenticate');

Route::middleware(['auth'])->group(function () {
    Route::view('/dashboard', 'generate-articles')->name('dashboard');
    Route::view('/history', 'history')->name('history');
    Route::post('/logout', [PasswordlessAuthenticationController::class, 'logout'])->name('logout');
});
