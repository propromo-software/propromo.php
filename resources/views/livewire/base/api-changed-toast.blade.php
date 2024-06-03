<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component {

    public $show_reload_toast = false;

    #[On('echo:api-changed,ApiChanged')]
    public function apiChanged()
    {
      $this->show_reload_toast = true;
    }
}; ?>

<div>

</div>
