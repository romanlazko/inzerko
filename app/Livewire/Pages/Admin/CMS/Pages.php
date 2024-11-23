<?php

namespace App\Livewire\Pages\Admin\CMS;

use App\Jobs\CreateSeedersJob;
use App\Livewire\Actions\Concerns\CategorySection;
use App\Livewire\Actions\SeedAction;
use App\Livewire\Layouts\AdminTableLayout;
use App\Models\Page;
use App\Models\Seeder;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Orangehill\Iseed\Facades\Iseed;

class Pages extends AdminTableLayout implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    use CategorySection;

    public function table(Table $table): Table
    {
        return $table
            ->query(Page::query())
            ->headerActions([
                SeedAction::make('pages')
                    ->seedTables([
                        'pages',
                        'blocks',
                    ]),
                CreateAction::make()
                    ->icon('heroicon-o-plus-circle')
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
                    ->extraModalWindowAttributes(['style' => 'background-color: #e5e7eb'])
                    ->visible($this->roleOrPermission(['create', 'manage'], 'page'))
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
                    ->button()
                    ->visible($this->roleOrPermission(['update', 'manage'], 'page')),
                DeleteAction::make('delete')
                    ->hiddenLabel()
                    ->button()
                    ->action(fn (Page $record) => $record->delete())
                    ->visible($this->roleOrPermission(['delete', 'manage'], 'page')),
                DeleteAction::make('forceDelete')
                    ->hiddenLabel()
                    ->button()
                    ->action(fn (Page $record) => $record->forceDelete())
                    ->visible($this->roleOrPermission(['force_delete', 'manage'], 'page'))
            ]);
    }
}