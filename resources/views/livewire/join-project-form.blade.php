<div>
    <div class="grid justify-center">
        <form wire:submit="save">
            <label class="text-primary-blue font-koulen text-2xl" for="url">JOIN A PROJECT: </label>
            <br>
            <div class="flex gap-5 w-max">

                <input type="text" id="url" value="https://github.com/propromo-software" placeholder="url"
                       filled
                       wire:model="projectUrl" class="p-0.5">

                <sl-button wire:click="save">JOIN</sl-button>
            </div>
        </form>

        @error('projectUrl')
            <span>{{$message}}</span>
        @enderror

    </div>
</div>
