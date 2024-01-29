<div class="flex justify-center">
    <form wire:submit="save" class="flex gap-2">
        <sl-input  type="text" id="url" value="https://github.com/propromo-software" placeholder="url" filled wire:model="projectUrl"></sl-input>
        <sl-button  wire:click="save">submit</sl-button>
    </form>

    @error('projectUrl')
        <span>{{$message}}</span>
    @enderror
</div>
