<div>
    <form wire:submit="save">
        <label class="text-primary-blue font-koulen text-2xl" for="url">JOIN A PROJECT: </label>
        <br>
        <div class="flex gap-5">

            <sl-input type="text" id="url" value="https://github.com/propromo-software"
                      placeholder="Here goes the project-url"
                      wire:model="projectUrl"
                      class="w-full"
            >
            </sl-input>
            <sl-button wire:click="save" wire:loading.attr="disabled">JOIN</sl-button>
        </div>
    </form>


    @if ($createProjectError)
        <sl-alert variant="danger" duration="3000" closable>
            <sl-icon slot="icon" name="exclamation-octagon"></sl-icon>
            <strong>Project creation-error</strong><br/>
            {{$createProjectError}}
        </sl-alert>
    @endif

    @error('projectUrl')
    <span>{{$message}}</span>
    @enderror
</div>
