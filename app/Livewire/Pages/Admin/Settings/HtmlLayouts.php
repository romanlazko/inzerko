<?php

namespace App\Livewire\Pages\Admin\Settings;

use App\Jobs\CreateSeedersJob;
use App\Livewire\Actions\CreateSeederAction;
use App\Livewire\Layouts\AdminTableLayout;
use App\Models\Attribute\AttributeSection;
use App\Models\HtmlLayout;
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
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Wiebenieuwenhuis\FilamentCodeEditor\Components\CodeEditor;

class HtmlLayouts extends AdminTableLayout implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(HtmlLayout::query())
            ->headerActions([
                CreateSeederAction::make('html_layouts')
                    ->seedTables([
                        'html_layouts',
                    ]),
                CreateAction::make()
                    ->icon('heroicon-o-plus-circle')
                    ->form([
                        Section::make()
                            ->schema([
                                TextInput::make('name')
                                    ->required(),

                                CodeEditor::make('blade')
                            ])
                            ->columns(1),
                    ])
                    ->visible($this->roleOrPermission(['create', 'manage'], 'html_layout'))
                    ->slideOver(),
            ])
            ->columns([
                TextColumn::make('name')
                    ->description(fn (HtmlLayout $record): string =>  $record?->slug),
                ToggleColumn::make('is_active'),
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
                                TextInput::make('name')
                                    ->required(),

                                CodeEditor::make('blade')
                            ])
                            ->columns(1),
                    ])
                    ->hiddenLabel()
                    ->button()
                    ->visible($this->roleOrPermission(['update', 'manage'], 'html_layout'))
                    ->slideOver(),

                DeleteAction::make()
                    ->hiddenLabel()
                    ->button()
                    ->visible($this->roleOrPermission(['delete', 'manage'], 'html_layout')),
            ]);
    }
}