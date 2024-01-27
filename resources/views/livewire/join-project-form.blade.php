<div>
    <form wire:submit="save" class="flex items-baseline gap-2">
        <sl-input class="w-80" type="text" id="url" value="https://github.com/propromo-software" placeholder="url" filled wire:model="projectUrl"></sl-input>
        <sl-button class="" wire:click="save">submit</sl-button>
    </form>

    @error('projectUrl')
        <span class="text-rose-600">{{$message}}</span>
    @enderror
</div>
