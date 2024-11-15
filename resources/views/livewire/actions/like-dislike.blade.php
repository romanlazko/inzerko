<button 
    wire:click="like()" 
    onclick="event.preventDefault()" 
    class="fa-solid fa-heart size-6 min-w-6 min-h-6 max-w-6 max-h-6 text-red-600 hover:text-red-800"
    title="{{ $userVote?->vote ? 'Dislike' : 'Like' }} {{ $announcement?->title }}"
>
    @if ($userVote?->vote)
        <x-heroicon-s-heart class="size-5"/>
    @else
        <x-heroicon-o-heart class="size-5"/>
    @endif
</button>