<?php

namespace App\Livewire\Components\Profile;

use App\Enums\ContactTypeEnum;
use Filament\Forms;
use Filament\Forms\Get;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ContactForm extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(auth()->user()->contacts()->getQuery())
            ->columns([
                IconColumn::make('type')
                    ->label(''),
                TextColumn::make('link')
                    ->label('')
                    ->grow(),
            ])
            ->emptyStateHeading(__('profile.update_communication_information_form.empty_state_heading'))
            ->emptyStateDescription(false)
            ->heading(__('profile.update_communication_information_form.title'))
            ->description(__('profile.update_communication_information_form.description'))
            ->headerActions([
                CreateAction::make('create')
                    ->label(__('profile.update_communication_information_form.add_contact'))
                    ->modalHeading(__('profile.update_communication_information_form.add_contact'))
                    ->modalDescription(__('profile.update_communication_information_form.description'))
                    ->icon('heroicon-c-plus-circle')
                    ->form([
                        Forms\Components\ToggleButtons::make('type')
                            ->options(ContactTypeEnum::class)
                            ->disableOptionWhen(fn (string $value): bool => 
                                auth()->user()
                                    ->contacts
                                    ->contains(fn ($contact) => 
                                        $contact->type->value === (integer) $value
                                    )
                            )
                            ->inline()
                            ->live()
                            ->hiddenLabel()
                            ->required(),
                        Forms\Components\TextInput::make('link')
                            ->required()
                            ->hiddenLabel()
                            ->visible(fn (Get $get) => $get('type'))
                            ->placeholder(fn (Get $get) => ContactTypeEnum::tryFrom($get('type'))?->getPlaceholder())
                            ->prefix(fn (Get $get) => ContactTypeEnum::tryFrom($get('type'))?->getPrefix()),
                    ])
                    ->action(function ($data) {
                        auth()->user()->contacts()->updateOrCreate([
                            'type' => $data['type'],
                        ], [
                            'link' => $data['link'],
                        ]);
                    })
                    ->modalSubmitAction(fn ($action) => 
                        $action->icon('heroicon-c-plus-circle')
                    )
                    ->modalWidth('xl')
                    ->createAnother(false)
            ])
            ->actions([
                DeleteAction::make()
                    ->hiddenLabel(),
            ])
            ->paginated(false);
    }
    
    public function create(): void
    {
        $this->form->saveRelationships();
    }
    public function render()
    {
        return view('livewire.components.profile.contact-form');
    }
}
