<?php

namespace App\Livewire\Pages\Admin\Settings;

use App\Jobs\CreateSeedersJob;
use App\Livewire\Actions\CreateSeederAction;
use App\Livewire\Layouts\AdminTableLayout;
use App\Models\Attribute\AttributeSection;
use App\Models\Seeder;
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
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextInputColumn;

class Sections extends AdminTableLayout implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(AttributeSection::query())
            ->groups([
                'type'
            ])
            ->defaultSort('order_number')
            ->defaultGroup('type')
            ->headerActions([
                CreateSeederAction::make('attribute_sections')
                    ->seedTables([
                        'attribute_sections',
                    ]),
                CreateAction::make()
                    ->icon('heroicon-o-plus-circle')
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
                    ->visible($this->roleOrPermission(['create', 'manage'], 'section'))
                    ->slideOver(),
            ])
            ->columns([
                TextInputColumn::make('order_number')
                    ->label('#Order'),
                TextColumn::make('name')
                    ->description(fn (AttributeSection $attribute_section): string =>  $attribute_section?->slug)
                    ->sortable(query: fn ($query, $direction) => $query->orderBy('slug', $direction)),
                SelectColumn::make('type')
                    ->options([
                        'filter' => 'Filter',
                        'create' => 'Create',
                        'show' => 'Show',
                    ]),
                ToggleColumn::make('is_active'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
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
                    ->visible($this->roleOrPermission(['update', 'manage'], 'section'))
                    ->slideOver(),

                ReplicateAction::make()
                    ->hiddenLabel()
                    ->button(),

                DeleteAction::make()
                    ->hiddenLabel()
                    ->button()
                    ->visible($this->roleOrPermission(['delete', 'manage'], 'section')),
            ]);
    }
}