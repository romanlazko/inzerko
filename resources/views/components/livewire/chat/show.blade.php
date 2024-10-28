
<div class="flex flex-col flex-1 overflow-hidden h-20 w-full bg-red-500" data="">
    <div id="messages" class="grid grid-cols-1 gap-2 p-2 flex-1 h-full overflow-auto w-full py-6">
        @foreach ($messages as $message)
            <div class="w-full bg-yellow-500 h-min">
                <div @class(['flex space-x-2', 'float-right right-0' => auth()->user()->id == $message->user_id])>
                    <div @class(['p-2 shadow-sm rounded-lg space-y-2', 'bg-blue-100 ml-2' => auth()->user()->id == $message->user_id, 'bg-gray-100 mr-2' => auth()->user()->id != $message->user_id])>
                        
                        <p class="text-sm break-all">{{ $message->message }}</p>

                        <p class="text-[8px] text-gray-500">
                            {{ $message->created_at->format('H:i') }} 
                            @if (auth()->user()->id == $message->user_id AND $message->read_at)
                                ✓
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
    



