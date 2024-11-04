<?php

namespace App\Livewire\Pages\Admin\CMS;

use App\Jobs\CreateSeedersJob;
use App\Livewire\Actions\Concerns\CategorySection;
use App\Livewire\Layouts\AdminTableLayout;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Page;
use App\Models\Sorting;
use Aws\Crypto\Polyfill\Key;
use Closure;
use Faker\Provider\ar_EG\Text;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class Pages extends AdminTableLayout implements HasForms, HasTable
{
    use CategorySection;

    public function table(Table $table): Table
    {
        return $table
            ->query(Page::query())
            ->headerActions([
                Action::make('Save Seeders')
                    ->action(function () {
                        CreateSeedersJob::dispatch([
                            'pages',
                            'blocks'
                        ]);
                    }),
                CreateAction::make()
                    ->form([
                        Section::make('title')
                            ->columns(2)
                            ->schema([
                                TextInput::make('title')
                                    ->required(),
                                KeyValue::make('alternames')
                                    ->required()
                                    ->default([
                                        'en' => '',
                                        'cs' => '',
                                        'ru' => '',
                                    ]),
                            ]),
                        Section::make('blocks')
                            ->columns(1)
                            ->schema([
                                Repeater::make('blocks')
                                    ->schema([
                                        TextInput::make('title'),
                                        MarkdownEditor::make('content')
                                            ->fileAttachmentsDisk('s3')
                                            ->fileAttachmentsDirectory('attachments')
                                    ])
                                    ->relationship()
                            ])
                    ])
                    ->slideOver()
                    ->extraModalWindowAttributes(['style' => 'background-color: #e5e7eb']),
            ])
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('slug')
                    ->copyable(),
                ToggleColumn::make('is_active'),
                ToggleColumn::make('is_header'),
                ToggleColumn::make('is_footer'),
                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->recordAction('edit')
            ->paginated(false)
            ->actions([
                EditAction::make()
                    ->form([
                        Section::make('title')
                            ->columns(2)
                            ->schema([
                                TextInput::make('title')
                                    ->required(),
                                KeyValue::make('alternames')
                                    ->required()
                                    ->default([
                                        'en' => '',
                                        'cs' => '',
                                        'ru' => '',
                                    ])
                            ]),
                        Section::make('blocks')
                            ->columns(1)
                            ->schema([
                                Repeater::make('blocks')
                                    ->schema([
                                        TextInput::make('title'),
                                        MarkdownEditor::make('content')
                                            ->fileAttachmentsDisk('local')
                                            ->fileAttachmentsDirectory('images')
                                    ])

                                    ->relationship()
                            ])
                    ])
                    ->slideOver()
                    ->extraModalWindowAttributes(['style' => 'background-color: #e5e7eb'])
                    ->hiddenLabel()
                    ->button(),
                DeleteAction::make()
                    ->hiddenLabel()
                    ->button()
            ]);
    }
}