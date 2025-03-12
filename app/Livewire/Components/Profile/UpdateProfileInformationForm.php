<?php

namespace App\Livewire\Components\Profile;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;

class UpdateProfileInformationForm extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public function editProfileAction(): Action
    {
        return EditAction::make('editProfile')
            ->form([
                Grid::make(4)
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('avatar')
                            ->collection('avatar')
                            ->avatar()
                            ->columnSpan(1),
                        Grid::make(1)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->columnSpan(1),
                                TextInput::make('email')
                                    ->required()
                                    ->email()
                                    ->columnSpan(1),
                            ])
                            ->columnSpan(3),
                    ]),
            ])
            ->modalHeading(__('profile.profile'))
            ->modalDescription(__('profile.update_profile_information_form.description'))
            ->icon('heroicon-c-pencil-square')
            ->record(auth()->user())
            ->after(fn () => 
                redirect()->route('profile.edit')->with([
                    'ok' => true,
                    'description' => __('profile.saved'),
                ])
            );
    }

    public function render()
    {
        return view('livewire.components.profile.update-profile-information-form');
    }
}
