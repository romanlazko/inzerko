<x-app-layout class="w-full max-w-7xl m-auto">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('profile.penalty.banned') }}
        </h2>
    </x-slot>

    <x-slot name="sidebar">
        <x-nav.profile/>
    </x-slot>

    @php
        $ban = auth()->user()->latestBan;
    @endphp

    <div class="w-full max-w-5xl m-auto py-5 space-y-6">
        <div class="p-4 bg-red-600 text-white rounded-2xl max-w-xl">
            <div class="text-sm">
                {{ __('profile.penalty.banned') }} 
            </div>
        </div>
        <x-ux.white-block>
            <div class="space-y-6">
                <p>
                    <span class="text-gray-500">{{ __('profile.penalty.reason') }}:</span> {{ __('profile.penalty.reasons.' . $ban->comment) }}
                </p>
                @if ($ban->isTemporary())
                    <p>
                        <span class="text-gray-500">{{ __('profile.penalty.expired') }}:</span> {{ $ban->expired_at->format('d.m.Y') }}
                    </p>
                @endif
            </div>
        </x-ux.white-block>
    </div>
</x-app-layout>