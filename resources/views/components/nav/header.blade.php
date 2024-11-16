<nav class="flex items-center justify-between bg-gray-900 space-x-6 h-12 max-w-7xl m-auto z-50">
	<div class="flex items-center justify-start space-x-6 text-sm">
		<x-application-logo />
	</div>

	<div class="md:flex w-full justify-between hidden">
		<div class="flex items-center justify-end space-x-3 text-white text-sm">
			@foreach ($header_pages as $page)
				<x-nav.link href="{{ route('page', $page->slug) }}" :active="request()->routeIs('page', $page->slug) AND request()->page?->slug == $page->slug">
					{{ $page->name }}
				</x-nav.link>
			@endforeach
		</div>

		<div class="flex items-center space-x-6 text-white">
			@auth
				<x-nav.link href="{{ route('profile.wishlist') }}" aria-label="Wishlist">
					<x-heroicon-s-heart class="size-5"/>
				</x-nav.link>

				<x-nav.link @click="$dispatch('open-chat')" tag="button" aria-label="Messages">
					<div class="relative leading-3 size-5 m-auto">
						<x-unread-messages/>
						<x-heroicon-s-chat-bubble-left-right class="size-5"/>
					</div>
				</x-nav.link>
				
				<x-nav.link href="{{ route('profile.edit') }}" aria-label="Profile">
					<x-heroicon-s-user class="size-5"/>
				</x-nav.link>
			@else
				<a href="{{ route('register') }}" class="hover:text-indigo-700">
					{{ __("components.navigation.register") }}
				</a>
				<a href="{{ route('login') }}" class="hover:text-indigo-700">
					{{ __("components.navigation.login") }}
				</a>
			@endauth
			
			<x-a-buttons.create href="{{ route('announcement.create') }}" class="">
				{{ __("components.navigation.create_new") }}
			</x-a-buttons.create>
		</div>
	</div>

	<div>

	</div>
	<div class="flex items-center space-x-2 w-min">
		<x-nav.locale class="px-6 md:px-0"/>

		<div class="flex items-center md:hidden relative">
			<div x-data="{ dropdownOpen: false }" class="relative">
				<button @click="dropdownOpen = ! dropdownOpen" class="flex text-white hover:text-indigo-700" title="Menu">
					<x-heroicon-c-bars-3-bottom-right class="size-5"/>
				</button>
	
				<div x-cloak :class="dropdownOpen ? 'block' : 'hidden'" @click="dropdownOpen = false" class="fixed inset-0 z-[35] transition-opacity"></div>
	
				<div x-cloak x-show="dropdownOpen" class="absolute right-0 z-40 mt-2 p-0 overflow-hidden bg-white rounded-md shadow-xl border">
					<x-nav.profile>
						@foreach ($header_pages as $page)
							@if ($loop->first) <hr> @endif
							<x-nav.responsive-link href="{{ route('page', $page->slug) }}" :active="request()->routeIs('page', $page->slug) AND request()->page?->slug == $page->slug">
								{{ $page->name }}
							</x-nav.responsive-link>
						@endforeach
					</x-nav.profile>
				</div>
			</div>
		</div>
	</div>
</nav>