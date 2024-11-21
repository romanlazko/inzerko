<?php

namespace App\Livewire\Pages\Admin\Settings;

use App\Livewire\Layouts\AdminTableLayout;
use App\Models\AttributeSection;
use Closure;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class Sections extends AdminTableLayout implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(AttributeSection::query())
            ->headerActions([
                CreateAction::make()
                    ->form([
                        Section::make()
                            ->schema([
                                KeyValue::make('alternames')
                                    ->label('Label')
                                    ->keyLabel(__('Language'))
                                    ->valueLabel(__('Value'))
                                    ->columnSpan(2)
                                    ->live(debounce: 500)
                                    ->default([
                                        'en' => '',
                                        'cs' => '',
                                        'ru' => '',
                                    ])
                                    ->rules([
                                        fn (): Closure => function (string $attribute, $value, Closure $fail) {
                                            if (!isset($value['en']) OR $value['en'] == '') 
                                                $fail('The :attribute must contain english translation.');
                                        },
                                    ])
                                    ->required()
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', str()->snake($state['en']))),

                                TextInput::make('slug')
                                    ->required(),

                                TextInput::make('order_number')
                                    ->helperText(__('Порядковый номер секции внутри формы.'))
                                    ->numeric()
                                    ->required(),
                            ])
                            ->columns(2),
                    ])
                    ->visible($this->roleOrPermission(['create', 'manage'], 'section')),
            ])
            ->columns([
                TextColumn::make('order_number')
                    ->label('#Order'),
                TextColumn::make('name')
                    ->description(fn (AttributeSection $attribute_section): string =>  $attribute_section?->slug),
                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->recordAction('edit')
            ->paginated(false)
            ->actions([
                EditAction::make()
                    ->form([
                        Section::make()
                            ->schema([
                                KeyValue::make('alternames')
                                    ->label('Label')
                                    ->keyLabel(__('Language'))
                                    ->valueLabel(__('Value'))
                                    ->columnSpan(2)
                                    ->live(debounce: 500)
                                    ->default([
                                        'en' => '',
                                        'cs' => '',
                                        'ru' => '',
                                    ])
                                    ->rules([
                                        fn (): Closure => function (string $attribute, $value, Closure $fail) {
                                            if (!isset($value['en']) OR $value['en'] == '') 
                                                $fail('The :attribute must contain english translation.');
                                        },
                                    ])
                                    ->required()
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', str()->snake($state['en']))),

                                TextInput::make('slug')
                                    ->required(),

                                TextInput::make('order_number')
                                    ->helperText(__('Порядковый номер секции внутри формы.'))
                                    ->numeric()
                                    ->required(),
                            ])
                            ->columns(2)
                    ])
                    ->hiddenLabel()
                    ->button()
                    ->visible($this->roleOrPermission(['update', 'manage'], 'section')),

                DeleteAction::make()
                    ->hiddenLabel()
                    ->button()
                    ->visible($this->roleOrPermission(['delete', 'manage'], 'section')),
            ]);
    }
}