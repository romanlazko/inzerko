<?php

namespace App\Livewire\Components\Profile;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class UpdatePasswordForm extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('profile.update_passport_form.title'))
                    ->description(__('profile.update_passport_form.description'))
                    ->schema([
                        TextInput::make('Current password')
                            ->label(__('profile.update_passport_form.current_password'))
                            ->password()
                            ->required()
                            ->currentPassword()
                            ->revealable(),
                        TextInput::make('password')
                            ->label(__('profile.update_passport_form.new_password'))
                            ->password()
                            ->required()
                            ->rule(Password::default())
                            ->autocomplete('new-password')
                            ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                            ->live(debounce: 500)
                            ->same('passwordConfirmation')
                            ->revealable(),
                        TextInput::make('passwordConfirmation')
                            ->label(__('profile.update_passport_form.new_password_confirmation'))
                            ->password()
                            ->required()
                            ->dehydrated(false)
                            ->revealable(),
                    ])
                    ->footerActions([
                        Action::make('save')
                            ->label(__('profile.save'))
                            ->icon('heroicon-c-cloud-arrow-down')
                            ->action(function () {
                                $data = $this->form->getState();
                        
                                auth()->user()->update([
                                    'password' => $data['password'],
                                ]);
                            })
                            ->after(fn () => 
                                redirect()->route('profile.security')->with([
                                    'ok' => true,
                                    'description' => __('profile.saved'),
                                ])
                            ),
                    ]),
            ])
            ->statePath('data');
    }

    public function render()
    {
        return view('livewire.components.profile.update-password-form');
    }
}
