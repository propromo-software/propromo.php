<?php

use Livewire\Volt\Component;
use \App\Models\Monitor;

new class extends Component {
    public Monitor $monitor;
    public string $pdfName = ''; // Add a property to bind to Shoelace input

    public function mount(Monitor $monitor)
    {
        $this->monitor = $monitor;
    }

    public function open_pdf()
    {
        return $this->redirect('/monitors/'.$this->monitor->id.'/pdf');
    }

    public function submit_form()
    {
        // Add functionality for the form submission here if needed
        // e.g., saving the PDF name or other inputs
    }
};
?>

<div>
    <div class="mx-8 mt-6 mb-4">
        <div class="flex gap-4 items-center mb-2">
            <a href="/monitors" title="Show Monitor" class="flex items-center">
                <sl-icon class="p-2 text-4xl rounded-md border-2 cursor-pointer text-primary-blue border-other-grey" name="arrow-left-short" wire:ignore></sl-icon>
            </a>
            <x-breadcrumbs :breadcrumbs="Breadcrumbs::generate('monitor', $monitor)" location="top" />
        </div>
        <div class="rounded-2xl border-2 border-other-grey">
            <livewire:monitors.card lazy="true" :monitor="$monitor"/>
            <div class="flex justify-between items-center m-8">
                <div class="flex gap-8 items-center">
                    <div>
                        <sl-icon name="info-circle" class="text-7xl font-bold text-primary-blue"></sl-icon>
                    </div>
                    <div>
                        <h1 class="text-4xl font-koulen text-primary-blue">
                            PDF-EDITOR
                        </h1>
                        <p class="font-light">Want the current project-status as a PDF-file? <br>
                            Check out the PDF-builder now!</p>
                    </div>
                    <sl-button variant="default" size="large" wire:click="open_pdf()">
                        <sl-icon slot="suffix" name="box-arrow-up-right"></sl-icon>
                        Open PDF-EDITOR
                    </sl-button>
                </div>

                <div>
                    <sl-button variant="default" size="large">
                        <a href="/monitors/{{ $monitor->id }}/contributions" class="flex items-center sl-button variant-default size-large">
                            <sl-icon slot="suffix" name="people-fill"></sl-icon>
                            COMMITS
                        </a>
                    </sl-button>
                </div>
            </div>
        </div>

        <div class="mt-8 rounded-2xl border-2 border-other-grey">
            <livewire:monitors.dashboard.index :monitor="$monitor" lazy="true"/>
        </div>

        <div class="grid grid-cols-1 gap-8 mt-8 md:grid-cols-2 2xl:grid-cols-3">
            <div class="md:col-span-2 2xl:col-span-1">
                <livewire:monitors.read-me-view :monitor="$monitor"/>
            </div>
            <div class="p-5 rounded-2xl border-2 border-other-grey">
                <livewire:monitors.deployments-view :monitor="$monitor"/>
            </div>
            <div class="p-5 rounded-2xl border-2 border-other-grey">
                <livewire:monitors.vulnerabilities-view :monitor="$monitor"/>
            </div>
            <div></div>
            <div class="p-5 rounded-2xl border-2 border-other-grey">
                <livewire:monitors.releases-view :monitor="$monitor"/>
            </div>
        </div>
    </div>

    <x-footer :breadcrumbs="Breadcrumbs::generate('monitor', $monitor)" />
</div>
