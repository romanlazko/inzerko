<?php

namespace App\Livewire\Components\Profile;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;

class DeleteProfileAction extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public function deleteProfileAction(): Action
    {
        return Action::make('deleteProfile')
            ->label(__('profile.delete_user_form.delete_account'))
            ->modalHeading(__('profile.delete_user_form.title'))
            ->icon('heroicon-m-trash')
            ->color('danger')
            ->form([
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->currentPassword()
                    ->label(__('profile.password'))
                    ->required(),
            ])
            ->modalSubmitAction(fn ($action) => 
                $action->label(__('profile.delete_user_form.delete_account'))
                    ->icon('heroicon-m-trash')
            )
            ->action(function (array $data) {
                $user = auth()->user();

                Auth::logout();

                $user->delete();

                session()->invalidate();
                session()->regenerateToken();
            })
            ->requiresConfirmation()
            ->after(fn () => 
                redirect()->route('home')
            );
    }
    public function render()
    {
        return view('livewire.components.profile.delete-profile-action');
    }
}
