
@props(['messages' => [], 'user_id' => null])

<div class="flex flex-col flex-1 overflow-hidden h-full w-full" data="">
    <div class="grid grid-cols-1 gap-2 p-2 overflow-auto w-full py-6">
        @foreach ($messages as $message)
            <div class="w-full h-min">
                <div @class(['flex space-x-2', 'float-right right-0' => $user_id == $message->user_id])>
                    <div @class(['p-2 shadow-sm rounded-lg space-y-2', 'bg-blue-100 ml-2' => $user_id == $message->user_id, 'bg-gray-100 mr-2' => $user_id != $message->user_id])>
                        
                        <p class="text-sm break-all">{{ $message->message }}</p>

                        <p class="text-[8px] text-gray-500">
                            {{ $message->created_at->format('H:i') }} 
                            @if ($user_id == $message->user_id AND $message->read_at)
                                ✓
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
    



