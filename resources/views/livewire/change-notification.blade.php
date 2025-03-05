<?php

use Livewire\Volt\Component;

new class extends Component {

    public function mount(){

    }

    public function fetchLatestResponse(){

        $this->dispatch('show-error-alert', [
            'head' => 'Create Monitor Error',
            'message' => $message
        ]);
    }

}; ?>

<div>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('post-created', (event) => {

            });
        });
    </script>
</div>
