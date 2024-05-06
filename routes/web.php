<?php

use App\Livewire\History;
use App\Livewire\Presets;
use App\Livewire\Pricing;
use App\Livewire\Articles;
use App\Livewire\AutoBlogs;
use App\Livewire\PresetView;
use App\Livewire\ArticleEdit;
use App\Livewire\HistoryView;
use App\Livewire\AutoBlogView;
use App\Livewire\Integrations;
use App\Livewire\Publications;
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

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['guest'])->group(function () {
    Route::view('/login', 'auth.login');
    Route::post('/login', [PasswordlessAuthenticationController::class, 'sendLink'])->middleware('throttle:3,1')->name('login');
    Route::get('/login/{user}', [PasswordlessAuthenticationController::class, 'authenticateUser'])->name('passwordless.authenticate');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/generate-articles', GenerateArticles::class)->name('dashboard');
    Route::get('/dashboard/history', History::class)->name('history');
    Route::get('/dashboard/history/{id}', HistoryView::class)->name('history.view');
    Route::get('/dashboard/articles', Articles::class)->name('articles');
    Route::get('/dashboard/articles/edit/{id}', ArticleEdit::class)->name('article.edit');

    # editorjs helper routes
    Route::post('/dashboard/articles/edit/{id}', [ArticleEdit::class, 'save'])->name('article.save');
    Route::post('/dashboard/articles/edit/{id}/assistant', [ArticleEdit::class, 'assistant'])->name('article.assistant');
    Route::post('/dashboard/articles/edit/{id}/uploadImage', [ArticleEdit::class, 'uploadImage'])->name('article.upload-image');
    Route::post('/dashboard/articles/edit/{id}/uploadImageByUrl', [ArticleEdit::class, 'uploadImageByUrl'])->name('article.upload-image-by-url');

    Route::get('/dashboard/presets', Presets::class)->name('presets');
    Route::get('/dashboard/presets/create', PresetView::class)->name('preset.create');
    Route::get('/dashboard/presets/{id}', PresetView::class)->name('preset.edit');
    Route::get('/dashboard/autoblogs', AutoBlogs::class)->name('autoblogs');
    Route::get('/dashboard/autoblogs/create', AutoBlogView::class)->name('autoblog.create');
    Route::get('/dashboard/autoblogs/{id}', AutoBlogView::class)->name('autoblog.edit');
    Route::get('/dashboard/integrations', Integrations::class)->name('integrations');
    Route::get('/dashboard/integrations/create', IntegrationView::class)->name('integration.create');
    Route::get('/dashboard/integrations/{id}', IntegrationView::class)->name('integration.edit');
    Route::get('/dashboard/publications', Publications::class)->name('publications');
    Route::get('/dashboard/pricing', Pricing::class)->name('pricing');

    Route::get('/logout', [PasswordlessAuthenticationController::class, 'logout'])->name('logout');
});
