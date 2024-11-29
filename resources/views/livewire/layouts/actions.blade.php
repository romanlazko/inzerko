<div> 
    <div class="w-full z-50 flex items-center space-x-2" onclick="event.preventDefault()">
        @foreach ($actions as $action)
            {{ $this->{$action} }} 
        @endforeach
    </div>
 
    <x-filament-actions::modals />
</div>
