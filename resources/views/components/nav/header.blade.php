<nav class="flex items-center justify-between bg-gray-900 space-x-6 h-12 max-w-7xl m-auto z-50">
	<div class="flex items-center justify-start space-x-6 text-sm">
		<x-application-logo />
	</div>

	<div class="md:flex w-full justify-between hidden">
		<div class="flex items-center justify-end space-x-3 text-white text-sm">
			<x-nav.link href="{{ route('profile.wishlist') }}" :active="request()->routeIs('profile.wishlist')">
				{{ __('components.navigation.about_us') }}
			</x-nav.link>

			<x-nav.link href="{{ route('profile.wishlist') }}" :active="request()->routeIs('profile.wishlist')">
				{{ __('components.navigation.privacy') }}
			</x-nav.link>

			<x-nav.link href="{{ route('profile.wishlist') }}" :active="request()->routeIs('profile.wishlist')">
				{{ __('components.navigation.contacts') }}
			</x-nav.link>
		</div>

		<div class="flex items-center space-x-6 text-white">
			@auth
				<x-nav.link href="{{ route('profile.wishlist') }}">
					<x-heroicon-s-heart class="size-5"/>
				</x-nav.link>

				<x-nav.link @click="$dispatch('open-chat')">
					<div class="relative leading-3 size-5 m-auto">
						@if ($unreadMessagesCount > 0)
							<p class="absolute text-[8px] text-white w-3 h-3 rounded-full bg-red-500 top-3 text-center content-center items-center">{{ $unreadMessagesCount }}</p>
						@endif
						<x-heroicon-s-chat-bubble-left-right class="size-5"/>
					</div>
				</x-nav.link>
				
				<x-nav.link href="{{ route('profile.edit') }}">
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
		<x-nav.locale class="md:w-min w-full"/>

		<div class="flex items-center md:hidden relative">
			<div x-data="{ dropdownOpen: false }" class="relative">
				<button @click="dropdownOpen = ! dropdownOpen" class="flex text-white hover:text-indigo-700">
					<x-heroicon-c-bars-3-bottom-right class="size-5"/>
				</button>
	
				<div x-cloak :class="dropdownOpen ? 'block' : 'hidden'" @click="dropdownOpen = false" class="fixed inset-0 z-[35] transition-opacity  bg-black opacity-50"></div>
	
				<div x-cloak x-show="dropdownOpen" class="absolute right-0 z-40 mt-2 p-0 overflow-hidden bg-white rounded-md shadow-xl border">
					<x-nav.profile>
						<hr>

						<x-nav.responsive-link href="{{ route('profile.wishlist') }}" :active="request()->routeIs('profile.wishlist')">
							{{ __('components.navigation.about_us') }}
						</x-nav.responsive-link>

						<x-nav.responsive-link href="{{ route('profile.wishlist') }}" :active="request()->routeIs('profile.wishlist')">
							{{ __('components.navigation.privacy') }}
						</x-nav.responsive-link>

						<x-nav.responsive-link href="{{ route('profile.wishlist') }}" :active="request()->routeIs('profile.wishlist')">
							{{ __('components.navigation.contacts') }}
						</x-nav.responsive-link>
					</x-nav.profile>
				</div>
			</div>
		</div>
	</div>
</nav>