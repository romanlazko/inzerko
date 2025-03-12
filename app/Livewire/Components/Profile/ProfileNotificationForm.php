<?php

namespace App\Livewire\Components\Profile;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

class ProfileNotificationForm extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    
    public function mount(): void
    {
        $this->form->fill(auth()->user()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('profile.new_message_notifications_form.title'))
                    ->description(__('profile.new_message_notifications_form.description'))
                    ->schema([
                        CheckboxList::make('notification_settings')
                            ->hiddenLabel()
                            // ->required()
                            ->options([
                                'telegram' => __('profile.new_message_notifications_form.telegram'),
                                'email' => __('profile.new_message_notifications_form.email'),
                            ]),
                    ])
                    ->compact(false)
                    ->footerActions([
                        Action::make('save')
                            ->label(__('profile.save'))
                            ->icon('heroicon-c-cloud-arrow-down')
                            ->action(function () {
                                auth()->user()->update($this->form->getState());
                            })
                            ->after(fn () => 
                                redirect()->route('profile.notification')->with([
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
        return view('livewire.components.profile.profile-notification-form');
    }
}
