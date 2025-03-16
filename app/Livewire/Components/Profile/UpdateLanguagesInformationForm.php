<?php

namespace App\Livewire\Components\Profile;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

class UpdateLanguagesInformationForm extends Component implements HasForms
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
                Section::make(__('profile.update_languages_information_form.title'))
                    ->description(__('profile.update_languages_information_form.description'))
                    ->schema([
                        CheckboxList::make('languages')
                            ->hiddenLabel()
                            ->required()
                            ->options([
                                'ru' => "🇷🇺 ".__('profile.update_languages_information_form.russian'),
                                'en' => "🇺🇸 ".__('profile.update_languages_information_form.english'),
                                'cz' => "🇨🇿 ".__('profile.update_languages_information_form.czech'),
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
                                redirect()->route('profile.edit')->with([
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
        return view('livewire.components.profile.update-languages-information-form');
    }
}
