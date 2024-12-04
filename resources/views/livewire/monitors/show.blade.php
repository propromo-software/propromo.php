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
        <a href="/monitors" title="Show Monitor" class="flex items-center mb-2">
            <sl-icon class="p-2 text-4xl border-2 rounded-md cursor-pointer text-primary-blue border-other-grey" name="arrow-left-short" wire:ignore></sl-icon>
        </a>
        <div class="border-2 border-other-grey rounded-2xl">
            <livewire:monitors.card lazy="true" :monitor="$monitor"/>
            <div class="flex items-center justify-between m-8">
                <div class="flex items-center gap-8">
                    <div>
                        <sl-icon name="info-circle" class="font-bold text-7xl text-primary-blue"></sl-icon>
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

        <div class="mt-8 border-2 border-other-grey rounded-2xl">
            <livewire:monitors.dashboard.index :monitor="$monitor" lazy="true"/>
        </div>

        <!-- Shoelace Input Field for PDF name or other data -->
        <div class="mt-8">
            <div class="p-5 border-2 border-other-grey rounded-2xl">
                <form wire:submit.prevent="submit_form">
                    <div class="mb-4">
                        <sl-input label="PDF Name" placeholder="Enter PDF name" size="large" filled
                                  wire:model.defer="pdfName"></sl-input>
                    </div>
                    <div class="mb-4">
                        <sl-select label="Status" placeholder="Select status" size="large" filled wire:model.defer="status">
                            <sl-menu-item value="draft">Draft</sl-menu-item>
                            <sl-menu-item value="final">Final</sl-menu-item>
                        </sl-select>
                    </div>
                    <sl-button type="submit" variant="primary" size="large">
                        <sl-icon slot="suffix" name="save"></sl-icon>
                        Save PDF
                    </sl-button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-8 mt-8">
            <div class="col-span-2">
                <livewire:monitors.read-me-view :monitor="$monitor"/>
            </div>
            <div class="p-5 border-2 border-other-grey rounded-2xl">
                <h2 class="mb-4 text-lg font-semibold">Deployments</h2>
                <livewire:monitors.deployments-view :monitor="$monitor"/>
            </div>
        </div>
    </div>
</div>
