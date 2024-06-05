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


<div>
    <div class="border-other-grey border-2 rounded-2xl p-8 mt-4 mx-8">
    <div class="flex items-center justify-between mb-5">

        <div class="flex items-center gap-3">
            <a title="Show Monitor" class="flex items-center">
                <sl-icon class="cursor-pointer text-primary-blue text-4xl rounded-md border-2 p-2 border-other-grey" name="arrow-left-short" wire:ignore></sl-icon>
            </a>
            <a class="flex items-center gap-1 rounded-md border-2 border-other-grey px-6 py-3" wire:click="fireMonitorHashChangedEvent" title="Show User">
                <sl-icon wire:ignore class="text-secondary-grey font-sourceSansPro text-xl font-bold" name="chat"></sl-icon>
                <div>
                    <div class="text-secondary-grey font-sourceSansPro text-lg font-bold">
                        CONTACT
                    </div>
                </div>
            </a>
        </div>


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

</div>

