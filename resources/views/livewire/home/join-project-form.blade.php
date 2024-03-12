<?php

use Livewire\Volt\Component;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Traits\ProjectJoiner;

new class extends Component {
    use ProjectJoiner;

    protected $rules = [
        'project_hash' => 'required|min:10|max:2048'
    ];
    public $project_hash;

    public function submit()
    {
        if (Auth::check()) {

            $project = $this->joinProject($this->project_hash);

            return redirect('/projects/' . $project->id);
        } else {
            return redirect('/register');
        }
    }
}; ?>

<div>
    <form wire:submit="submit">
        <label class="text-primary-blue font-koulen text-2xl" for="url">JOIN A PROJECT: </label>
        <br>
        <div class="flex gap-5">
            <sl-input type="text" id="url"
                      placeholder="Here goes the project-hash"
                      wire:model="project_hash"
                      wire:ignore
                      class="w-full"
            >
            </sl-input>


            <sl-button type="submit" wire:loading.attr="disabled" wire:ignore>JOIN</sl-button>
        </div>
        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
           href="{{ url('create-monitor') }}">
            No monitor yet?
        </a>
    </form>

    @error('project_hash')
        <span>{{$message}}</span>
    @enderror
</div>
