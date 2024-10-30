<button 
    wire:click="like()" 
    onclick="event.preventDefault()" 
    @class([
        'fa-solid fa-heart text-gray-400 hover:text-red-400 size-6 min-w-6 min-h-6 max-w-6 max-h-6', 
        'text-red-600 hover:text-red-800' => $userVote?->vote
        
    ])
    title="{{ $userVote?->vote ? 'Dislike' : 'Like' }} {{ $announcement?->title }}"
>
    <x-heroicon-s-heart class="size-5"/>
</button>