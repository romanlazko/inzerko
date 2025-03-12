@if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
    <div class="p-4 bg-red-600 text-white rounded-2xl max-w-xl">
        <div class="mb-4 text-sm">
            {{ __('profile.verify_email.description') }}
        </div>
    
        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('profile.verify_email.title') }}
            </div>
        @endif
    
        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
    
                <div>
                    <x-filament::button 
                        type="submit"
                        class="w-full"
                        color="dark"
                        icon="heroicon-c-arrow-path"
                    >
                        {{ __('profile.verify_email.resend') }}
                    </x-filament::button>
                </div>
            </form>
        </div>
    </div>
@endif