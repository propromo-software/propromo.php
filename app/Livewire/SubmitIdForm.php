<?php

namespace App\Livewire;

use Livewire\Component;

class SubmitIdForm extends Component
{
    public $url;

    public function render()
    {
        return view('livewire.submit-id-form');
    }
}
