<div>
        <form wire:submit="save">
            <label class="text-primary-blue font-koulen text-2xl" for="url">JOIN A PROJECT: </label>
            <br>
            <div class="flex gap-5">

                <sl-input type="text" id="url" value="https://github.com/propromo-software" placeholder="Here goes the project-url"
                          wire:model="projectUrl"
                          class="w-full"
                >
                </sl-input>
                <sl-button wire:click="save">JOIN</sl-button>
            </div>
        </form>

        @error('projectUrl')
            <span>{{$message}}</span>
        @enderror
</div>
