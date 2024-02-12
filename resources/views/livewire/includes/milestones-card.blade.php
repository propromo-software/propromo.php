<div class="rounded-md border-2 border-other-grey p-5 flex gap-2 items-center mt-8 ">
    @foreach($milestones as $milestone)
        @include('livewire.includes.milestone-card', ['milestone' => $milestone])
        <div class="border-primary-blue bg-primary-blue w-12 h-8 rounded-md">
            <!-- Additional content related to milestone -->
        </div>
    @endforeach
</div>
