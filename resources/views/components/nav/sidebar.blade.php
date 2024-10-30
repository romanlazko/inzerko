<div {{ $attributes->merge(['class' => "flex-1 flex flex-col overflow-hidden lg:flex-none lg:overflow-visible lg:flex-row w-full"]) }}>
	<div class="justify-between flex items-center p-2 px-4 space-x-4 bg-gray-900 z-40 lg:hidden">
		<a href="{{ route('announcement.index') }}">
			
		</a>
		<button @click="sidebarOpen = false" class="text-gray-200 focus:outline-none lg:hidden" title="Close sidebar">
			<x-heroicon-s-x-mark class="size-5"/>
		</button>
	</div>

	<nav class="flex-1 overflow-y-auto h-full lg:flex-none lg:overflow-visible lg:flex-row w-full px-3 py-4">
		{{ $slot }}
	</nav>
</div>