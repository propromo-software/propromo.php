<div>
    <form wire:submit="save" class="flex items-baseline gap-2">
        <sl-input class="w-11/12" type="text" id="url" value="https://github.com/propromo-software" placeholder="url" filled wire:model="projectUrl"></sl-input>
        <sl-button class="" wire:click="save">submit</sl-button>
    </form>
</div>
