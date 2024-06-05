<?php

use Livewire\Volt\Component;
use \App\Models\Milestone;


new class extends Component {

    public Milestone $milestone;

    public $scope;
    protected $queryString = ['scope'];

    public function mount(Milestone $milestone)
    {
        $this->milestone = $milestone;
    }


}; ?>


<div class="mt-4 mx-8">
    <div class="flex items-center justify-between mb-5">
        <a  class="text-secondary-grey text-lg font-sourceSansPro font-bold rounded-md border-2 border-other-grey px-6 py-3"  title="Show Monitor">
           fsafadsf
        </a>

        <div class="flex items-center gap-2">
            <a class="flex items-center gap-1 rounded-md border-2 border-other-grey px-6 py-3" wire:click="fireMonitorHashChangedEvent"  title="Show User">
                <sl-icon wire:ignore class="text-secondary-grey font-sourceSansPro text-xl font-bold" name="chat"></sl-icon>
                <div>
                    <div class="text-secondary-grey font-sourceSansPro text-lg font-bold">
                        CONTACT
                    </div>
                </div>
            </a>

            <sl-icon-button class="text-3xl text-secondary-grey" name="arrow-repeat" label="Reload" type="submit" wire:ignore wire:click="reload_repositories"></sl-icon-button>
        </div>
    </div>

    <div class="border-other-grey border-2 rounded-2xl p-8 m-2">
        <livewire:tasks.list/>
    </div>
</div>

