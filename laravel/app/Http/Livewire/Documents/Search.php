<?php

namespace App\Http\Livewire\Documents;

use Livewire\Component;

class Search extends Component
{
    protected $listeners = [DocumentEvents::CREATED => "render"];
    
    public function render()
    {
        return view('livewire.documents.search');
    }
}
