<?php

namespace App\Livewire;

use Livewire\Component;

class ArticleView extends Component
{
    public $showDrawer = false;
    public $selectedArticle;

    public function mount()
    {
        // $this->showDrawer = true;
    }

    public function render()
    {
        return view('livewire.article-view');
    }
}
