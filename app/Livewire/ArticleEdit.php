<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\AIService;
use Illuminate\Http\Request;
use App\Helpers\PromptBuilder;
use Illuminate\Support\Facades\Route;

class ArticleEdit extends Component
{
   public $article;

   public function mount()
   {
      $article_id = Route::current()->parameter('id');
      $this->article = auth()->user()->articles()->find($article_id);
   }

   public function save(Request $request)
   {
      $article_id = Route::current()->parameter('id');
      $article = auth()->user()->articles()->find($article_id);
      $article->content = $request->input('data');
      $article->save();

      return response()->json(['success' => true]);
   }

   public function assistant(Request $request)
   {
      $articleHTML = $request->input('articleHTML');
      $selectedText = $request->input('selectedText');
      $prompt = $request->input('prompt');

      $systemPrompt = new PromptBuilder();
      $userPrompt = new PromptBuilder();

      $systemPrompt->introduceWriter();
      $userPrompt->promptArticleAssistance($articleHTML, $selectedText, $prompt);

      $recommendations = AIService::sendPrompt($systemPrompt->build('JSON'), $userPrompt->build('JSON'));

      return response()->json($recommendations);
   }

   public function render()
   {
      return view('livewire.article-edit')->extends('layouts.dashboard')->section('dashboard-content');
   }
}
