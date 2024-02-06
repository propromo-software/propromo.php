<div>
        <div class="flex">
            <form wire:submit="save">
                <div class="flex gap-2 place-items-end">
                    <div>
                        <label class="text-primary-blue font-koulen text-2xl" for="url">JOIN A PROJECT: </label>
                        <br>
                        <sl-input type="text" id="url" value="https://github.com/propromo-software" placeholder="url"
                                  filled
                                  wire:model="projectUrl" class="w-72 p-0.5"></sl-input>
                    </div>

                    <sl-button class="" wire:click="save">submit</sl-button>

                </div>
            </form>

            @error('projectUrl')
                <span>{{$message}}</span>
            @enderror
    </div>
</div>
