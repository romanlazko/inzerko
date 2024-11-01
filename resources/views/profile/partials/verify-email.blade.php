@if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
    <div class="p-4 bg-red-600 text-white rounded-xl">
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
                    <x-buttons.primary>
                        {{ __('profile.verify_email.resend') }}
                    </x-buttons.primary>
                </div>
            </form>
        </div>
    </div>
@endif