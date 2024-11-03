<?php

namespace App\Livewire\Pages\Admin\Settings;

use App\Jobs\CreateSeedersJob;
use App\Livewire\Actions\CreateAttributeAction;
use App\Livewire\Actions\EditAttributeAction;
use App\Livewire\Layouts\AdminTableLayout;
use App\Models\Attribute;
use App\Models\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;

class Attributes extends AdminTableLayout implements HasForms, HasTable
{   
    public function table(Table $table): Table
    {
        return $table
            ->heading("All attributes: " . Attribute::count())
            ->query(Attribute::with('categories', 'filterSection', 'createSection', 'showSection', 'attribute_options', 'group'))
            ->groups([
                Group::make('filterSection.slug')
                    ->getTitleFromRecordUsing(fn (Attribute $attribute): string => $attribute?->filterSection?->name ?? 'null')
                    ->getDescriptionFromRecordUsing(fn (Attribute $attribute): string => "#{$attribute?->filterSection?->order_number} - {$attribute?->filterSection?->slug}")
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),
                Group::make('createSection.slug')
                    ->getTitleFromRecordUsing(fn (Attribute $attribute): string => $attribute?->createSection?->name ?? 'null')
                    ->getDescriptionFromRecordUsing(fn (Attribute $attribute): string => "#{$attribute?->createSection?->order_number} - {$attribute?->createSection?->slug}")
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),
                Group::make('showSection.slug')
                    ->getTitleFromRecordUsing(fn (Attribute $attribute): string => $attribute?->showSection?->name ?? 'null')
                    ->getDescriptionFromRecordUsing(fn (Attribute $attribute): string => "#{$attribute?->showSection?->order_number} - {$attribute?->showSection?->slug}")
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),
            ])
            ->defaultSort(function () use ($table){
                return match ($table->getGrouping()?->getRelationshipName()) {
                    'filterSection' => 'filter_layout->order_number',
                    'createSection' => 'create_layout->order_number',
                    'showSection' => 'show_layout->order_number',
                    default => 'created_at',
                };
            })
            ->defaultGroup('createSection.slug')
            ->columns([
                TextColumn::make('order')
                    ->state(fn (Attribute $attribute) => match ($table->getGrouping()?->getRelationshipName()) {
                        'filterSection' => $attribute->filter_layout['order_number'] ?? 0,
                        'createSection' => $attribute->create_layout['order_number'] ?? 0,
                        'showSection' => $attribute->show_layout['order_number'] ?? 0,
                        default => null,
                    }),

                TextColumn::make('label')
                    ->description(fn (Attribute $attribute): string =>  $attribute?->name . ($attribute?->suffix ? " ({$attribute?->suffix})" : '')),

                TextColumn::make('Layout')
                    ->state(fn (Attribute $attribute) => 
                        collect(['filter', 'create', 'show'])
                            ->map(function($section) use ($attribute) {
                                $layout = $attribute->{$section . '_layout'} ?? [];
                                $type = $layout['type'] ?? null;
                                $order_number = $layout['order_number'] ?? null;
                                return "#{$order_number} - {$section}Section: {$type}";
                            })
                            ->toArray()
                    )
                    ->badge()
                    ->color('warning'),

                TextColumn::make('group.slug')
                    ->badge()
                    ->state(fn (Attribute $record) => $record->group ? "#{$record->group_layout['order_number']} - {$record->group?->slug}": null)
                    ->color('danger'),
                    
                TextColumn::make('create_layout.rules')
                    ->label('Rules')
                    ->badge()
                    ->color('danger'),

                TextColumn::make('attribute_options')
                    ->state(fn (Attribute $record) => $record->attribute_options->pluck('name'))
                    ->badge()
                    ->grow(false),

                TextColumn::make('categories')
                    ->state(fn (Attribute $record) => $record->categories->pluck('name'))
                    ->badge()
                    ->color('success')
                    ->grow(false),
            ])
            ->headerActions([
                Action::make('Create seeder')
                    ->form([
                        Select::make('seeders')
                            ->multiple()
                            ->options([
                                'categories' => 'Category',
                                'attributes' => 'Attribute',
                                'attribute_category' => 'AttributeCategory',
                                'attribute_options' => 'AttributeOption',
                                'attribute_sections' => 'AttributeSection',
                                'attribute_groups' => 'Groups',
                                'media' => 'Media',
                                'sortings' => 'Sortings',
                                'pages' => 'Pages',
                                'blocks' => 'Blocks',
                            ])
                    ])
                    ->action(function (array $data) {
                        CreateSeedersJob::dispatch($data['seeders']);
                    })
                    ->hidden(app()->environment('production'))
                    ->slideOver()
                    ->closeModalByClickingAway(false),
                CreateAttributeAction::make(),
            ])
            ->actions([
                EditAttributeAction::make(),
                DeleteAction::make()
                    ->hiddenLabel()
                    ->button()
            ])
            ->recordAction('edit')
            ->paginated(false)
            ->filters([
                SelectFilter::make('category')
                    ->options(Category::all()->groupBy('parent.name')->map->pluck('name', 'id'))
                    ->query(fn ($query, $data) => 
                        $query->when($data['value'], fn ($query) => $query->whereHas('categories', fn ($query) => $query->where('category_id', $data['value'])))
                    )
            ]);
    }
}
