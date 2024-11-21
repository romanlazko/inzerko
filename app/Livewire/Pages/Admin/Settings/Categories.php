<?php

namespace App\Livewire\Pages\Admin\Settings;

use App\Enums\CardLayout;
use App\Livewire\Layouts\AdminTableLayout;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\TelegramChat;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class Categories extends AdminTableLayout implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public $category;

    public $category_attributes;

    public function mount($category = null)
    {
        if ($category) {
            $this->category = Category::find($category);
        }

        $this->category_attributes = Attribute::with('createSection')
            ->when($category, function ($query) {
                return $query->whereNotIn('id', $this->category->parentsAndSelf->pluck('attributes')->flatten()->pluck('id')->toArray());
            })
            ->get()
            ->groupBy('createSection.slug')
            ->map
            ->pluck('label', 'id')
            ->toArray();
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->heading($this->category?->name ?? "All categories")
            ->query(Category::where('parent_id', $this->category?->id ?? null))
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->collection('categories'),
                TextColumn::make('name')
                    ->description(fn (Category $category): string => $category->slug)
                    ->url(fn (Category $category): string => route('admin.setting.categories', $category)),
                ToggleColumn::make('is_active'),
                ToggleColumn::make('has_attachments'),
                TextColumn::make('children')
                    ->state(function (Category $record) {
                        return $record->children?->pluck('name');
                    })
                    ->badge()
                    ->color('success'),
                TextColumn::make('attributes')
                    ->state(function (Category $record) {
                        return $record->attributes?->pluck('label');
                    })
                    ->badge(),
                TextColumn::make('channels')
                    ->state(function (Category $record) {
                        return $record->channels?->pluck('title');
                    })
                    ->color('warning')
                    ->badge(),
            ])
            ->headerActions([
                Action::make('back')
                    ->label($this->category?->parent?->name ?? "All")
                    ->icon('heroicon-o-arrow-left-circle')
                    ->url(route('admin.setting.categories', $this->category?->parent?->id))
                    ->hidden(fn () => $this->category == null),
                CreateAction::make()
                    ->model(Category::class)
                    ->icon('heroicon-o-plus-circle')
                    ->form([
                        Section::make()
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        Grid::make(1)
                                            ->schema([
                                                SpatieMediaLibraryFileUpload::make('Image')
                                                    ->collection('categories')
                                                    ->imageEditor(),
                                            ])
                                            ->columnSpan(2),
                                        Grid::make(1)
                                            ->schema([
                                                Select::make('attributes')
                                                    ->relationship('attributes')
                                                    ->multiple()
                                                    ->options($this->category_attributes),
                                            ])
                                            ->columnSpan(2)
                                ]),
                        ])
                        ->columnSpan(1),
                    
                        Section::make()
                            ->schema([
                                KeyValue::make('alternames')
                                    ->keyLabel('Lang')
                                    ->default([
                                        'en' => '',
                                        'cs' => '',
                                        'ru' => '',
                                    ])
                                    ->live()
                                    ->afterStateUpdated(fn ($state, Set $set) => $set('slug', str()->slug($state['en']. '-'))),

                                TextInput::make('slug'),

                                TextInput::make('parent_id')
                                    ->hiddenLabel()
                                    ->default($this->category?->id ?? null)
                                    ->extraAttributes([
                                        'class' => 'hidden'
                                    ])
                            ])
                            ->columns(1),
                    ])
                    ->extraModalWindowAttributes(['style' => 'background-color: #e5e7eb'])
                    ->slideOver()
                    ->closeModalByClickingAway(false)
                    ->visible($this->roleOrPermission(['create', 'manage'], 'category')),
            ])
            ->actions([
                EditAction::make()
                    ->modalHeading(fn ($record) => $record?->name)
                    ->form([
                        Section::make()
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        Grid::make(1)
                                            ->schema([
                                                SpatieMediaLibraryFileUpload::make('Image')
                                                    ->collection('categories')
                                                    ->imageEditor(),
                                            ])
                                            ->columnSpan(2),
                                        Grid::make(1)
                                            ->schema([
                                                Select::make('attributes')
                                                    ->relationship('attributes')
                                                    ->multiple()
                                                    ->options($this->category_attributes),
                                            ])
                                            ->columnSpan(2)
                                ]),
                        ])
                        ->columnSpan(1),
                    
                        Section::make()
                            ->schema([
                                KeyValue::make('alternames')
                                    ->keyLabel('Lang')
                                    ->default([
                                        'en' => '',
                                        'cs' => '',
                                        'ru' => '',
                                    ])
                                    ->live()
                                    ->afterStateUpdated(fn ($state, Set $set) => $set('slug', str()->slug($state['en']. '-'))),

                                TextInput::make('slug'),

                                TextInput::make('parent_id')
                                    ->hiddenLabel()
                                    ->default($this->category?->id ?? null)
                                    ->extraAttributes([
                                        'class' => 'hidden'
                                    ])
                            ])
                            ->columns(1),
                        Section::make()
                            ->schema([
                                Select::make('channels')
                                    ->relationship('channels')
                                    ->multiple()
                                    ->options(TelegramChat::where('type', 'channel')->pluck('title', 'id'))
                            ])
                    ])
                    ->slideOver()
                    ->extraModalWindowAttributes(['style' => 'background-color: #e5e7eb'])
                    ->hiddenLabel()
                    ->button()
                    ->closeModalByClickingAway(false)
                    ->visible($this->roleOrPermission(['update', 'manage'], 'category')),

                DeleteAction::make()
                    ->record($this->category)
                    ->hiddenLabel()
                    ->button()
                    ->visible($this->roleOrPermission(['delete', 'manage'], 'category'))
            ])
            ->paginated(false)
            ->recordAction('edit');
    }
}
