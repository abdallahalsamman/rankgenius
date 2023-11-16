<?php

namespace App\Livewire;

use Livewire\Component;

class AutoBlogs extends Component
{

    public $autoBlogs, $autoBlogIdToDelete = null, $deleteModal = false;

    public function deleteConfirm($id)
    {
        $this->autoBlogIdToDelete = $id;
        $this->deleteModal = true;
        $this->setAndSortAutoBlogs();
    }

    public function delete($id)
    {
        $this->autoBlogs->find($id)->delete();
        $this->setAndSortAutoBlogs();
        $this->deleteModal = false;
    }

    public function setAndSortAutoBlogs() {
        $this->autoBlogs = auth()->user()->autoBlogs->sortBy('created_at');
    }

    public function mount()
    {
        $this->setAndSortAutoBlogs();
    }

    public function render()
    {
        return view('livewire.auto-blogs')->layout('layouts.dashboard');
    }
}
