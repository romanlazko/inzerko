<div class="w-full bg-white rounded-2xl p-4 border">
    <div class="flex justify-between items-center">
        <div class="text-lg font-semibold text-orange-600">Await Moderation:</div>
        <div class="text-lg font-semibold">{{ $count }}</div>
    </div>
    <x-a-buttons.button href="{{ route('admin.announcement.moderation') }}" class="w-min justify-center mt-2 ring-1 ring-gray-300 whitespace-nowrap hover:bg-slate-100">
        Moderate ➜
    </x-a-buttons.button>
</div>