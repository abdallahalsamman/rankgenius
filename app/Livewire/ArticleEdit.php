<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\AIService;
use Illuminate\Http\Request;
use App\Helpers\PromptBuilder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Usernotnull\Toast\Concerns\WireToast;

class ArticleEdit extends Component
{
   use WireToast;

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

   public function uploadImage(Request $request)
   {
      $article_id = Route::current()->parameter('id');

      $file = $request->file('image');
      $fileSize = $file->getSize();
      if ($fileSize > 5 * 1024 * 1024) {
         return response()->json(['error' => 'File size exceeds 5MB']);
      }
      $fileResource = $file->store('images/' . $article_id . '/', 'r2');

      return response()->json(['success' => 1, 'file' => ['url' => Storage::url($fileResource)]]);
   }

   public function uploadImageByUrl(Request $request)
   {
      $article_id = Route::current()->parameter('id');

      $imageUrl = $request->input('url');
      $imageBytes = file_get_contents($imageUrl);

      if ($imageBytes === false) {
         return response()->json(['error' => 'Failed to download image']);
      }

      if (strlen($imageBytes) > 5 * 1024 * 1024) {
         return response()->json(['error' => 'File size exceeds 5MB']);
      }

      $imageType = exif_imagetype($imageUrl);
      if ($imageType === false) {
         return response()->json(['error' => 'Failed to determine image type']);
      }

      if (!in_array($imageType, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG])) {
         return response()->json(['error' => 'Invalid image type']);
      }

      $imagePath = 'images/' . $article_id . '/' . basename($imageUrl);
      Storage::put($imagePath, $imageBytes);

      return response()->json(['success' => 1, 'file' => ['url' => Storage::url($imagePath)]]);
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
