<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class ArticleEdit extends Component
{
   public $article;

   public function mount()
   {
      $article_id = Route::current()->parameter('id');
      $this->article = auth()->user()->articles()->where('id', $article_id)->first();
   }

   public function save(Request $request)
   {
      $article_id = Route::current()->parameter('id');
      $article = auth()->user()->articles()->where('id', $article_id)->first();
      $article->content = $request->input('data');
      $article->save();

      return response()->json(['success' => true]);
   }

   public function render()
   {
      return view('livewire.article-edit')->extends('layouts.dashboard')->section('dashboard-content');
   }
}
