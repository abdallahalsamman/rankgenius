<?php

use App\Livewire\History;
use App\Livewire\HistoryView;
use App\Livewire\GenerateArticles;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordlessAuthenticationController;
use App\Livewire\Articles;
use App\Livewire\Articleview;
use App\Livewire\PresetEdit;
use App\Livewire\Presets;
use App\Livewire\PresetView;

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

Route::middleware(['guest'])->group(function () {
    Route::view('/login', 'auth.login');
    Route::post('/login', [PasswordlessAuthenticationController::class, 'sendLink'])->middleware('throttle:3,1')->name('login');
    Route::get('/login/{user}', [PasswordlessAuthenticationController::class, 'authenticateUser'])->name('passwordless.authenticate');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', GenerateArticles::class)->name('dashboard');
    Route::get('/history', History::class)->name('history');
    Route::get('/history/{id}', HistoryView::class)->name('history-view');
    Route::get('/articles', Articles::class)->name('articles');
    Route::get('/articles/{id}', Articleview::class)->name('article-view');
    Route::get('/presets', Presets::class)->name('presets');
    Route::get('/presets/create', PresetView::class)->name('preset-view');
    Route::get('/presets/{id}', PresetEdit::class)->name('preset-edit');

    Route::get('/logout', [PasswordlessAuthenticationController::class, 'logout'])->name('logout');
});

