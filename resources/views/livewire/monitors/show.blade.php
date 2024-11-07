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
    <div class="mx-8 mt-6">
        <a href="/monitors" title="Show Monitor" class="flex items-center mb-2">
            <sl-icon class="cursor-pointer text-primary-blue text-4xl rounded-md border-2 p-2 border-other-grey" name="arrow-left-short" wire:ignore></sl-icon>
        </a>
        <div class="border-other-grey border-2 rounded-2xl">
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
                        <sl-icon slot="suffix" name="people-fill"></sl-icon>
                        COMMITS
                    </sl-button>
                </div>
            </div>
        </div>

        <div class="border-other-grey border-2 rounded-2xl mt-8">
            <livewire:monitors.dashboard.index :monitor="$monitor" lazy="true"/>
        </div>

        <!-- Shoelace Input Field for PDF name or other data -->
        <div class="mt-8">
            <div class="border-other-grey border-2 rounded-2xl p-5">
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

        <div class="mt-8 grid grid-cols-3 gap-8">
            <div class="col-span-2">
                <livewire:monitors.read-me-view :monitor="$monitor"/>
            </div>
            <div class="border-other-grey border-2 rounded-2xl p-5">Deployments</div>
        </div>
    </div>
</div>
