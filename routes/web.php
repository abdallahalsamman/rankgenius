<?php

use App\Livewire\History;
use App\Livewire\Presets;
use App\Livewire\Articles;
use App\Livewire\AutoBlogs;
use App\Livewire\PresetView;
use App\Livewire\HistoryView;
use App\Livewire\AutoBlogView;
use App\Livewire\Integrations;
use App\Livewire\IntegrationView;
use App\Livewire\GenerateArticles;
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

Route::middleware(['guest'])->group(function () {
    Route::view('/login', 'auth.login');
    Route::post('/login', [PasswordlessAuthenticationController::class, 'sendLink'])->middleware('throttle:3,1')->name('login');
    Route::get('/login/{user}', [PasswordlessAuthenticationController::class, 'authenticateUser'])->name('passwordless.authenticate');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', GenerateArticles::class)->name('dashboard');
    Route::get('/history', History::class)->name('history');
    Route::get('/history/{id}', HistoryView::class)->name('history.view');
    Route::get('/articles', Articles::class)->name('articles');
    Route::get('/presets', Presets::class)->name('presets');
    Route::get('/presets/create', PresetView::class)->name('preset.create');
    Route::get('/presets/{id}', PresetView::class)->name('preset.edit');
    Route::get('/autoblogs', AutoBlogs::class)->name('autoblogs');
    Route::get('/autoblogs/create', AutoBlogView::class)->name('autoblog.create');
    Route::get('/autoblogs/{id}', AutoBlogView::class)->name('autoblog.edit');
    Route::get('/integrations', Integrations::class)->name('integrations');
    Route::get('/integrations/create', IntegrationView::class)->name('integration.create');
    Route::get('/integrations/{id}', IntegrationView::class)->name('integration.edit');

    Route::get('/logout', [PasswordlessAuthenticationController::class, 'logout'])->name('logout');
});

