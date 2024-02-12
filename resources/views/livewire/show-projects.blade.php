
<div class="w-full mx-8 border-other-grey border-2 rounded-2xl mt-6">
    @foreach($projects as $project)
        @include('livewire.includes.project-card')
    @endforeach
</div>
