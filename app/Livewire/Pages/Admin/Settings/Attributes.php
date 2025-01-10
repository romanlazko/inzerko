<?php

namespace App\Livewire\Pages\Admin\Settings;

use App\Filament\Exports\Attribute\AttributeExporter;
use App\Jobs\CreateSeedersJob;
use App\Livewire\Actions\CreateAttributeAction;
use App\Livewire\Actions\EditAttributeAction;
use App\Livewire\Actions\CreateSeederAction;
use App\Livewire\Layouts\AdminTableLayout;
use App\Models\Attribute\Attribute;
use App\Models\Category;
use App\Models\Seeder;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class Attributes extends AdminTableLayout implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
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

                ToggleColumn::make('is_active'),

                TextColumn::make('Layout')
                    ->state(fn (Attribute $attribute) => 
                        collect(['filter', 'create', 'show'])
                            ->map(function($section) use ($attribute) {
                                $layout = $attribute->{$section . '_layout'} ?? [];
                                $type = $layout['type'] ?? null;
                                $order_number = $layout['order_number'] ?? null;
                                return "#{$order_number} - {$section}: {$type}";
                            })
                            ->toArray()
                    )
                    ->badge()
                    ->listWithLineBreaks()
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
                    ->state(fn (Attribute $record) => $record->attribute_options->map(function ($option) {
                        $params = implode(', ', array_filter([
                            ($option->is_default ? 'Default' : null),
                            ($option->is_null ? 'Null' : null),
                        ]));
                        return $option->name . ($params ? " ({$params})" : '');
                    }))
                    ->badge()
                    ->grow(false),

                TextColumn::make('categories')
                    ->state(fn (Attribute $record) => $record->categories->pluck('name'))
                    ->badge()
                    ->color('success')
                    ->grow(false),
            ])
            ->headerActions([
                CreateSeederAction::make('attributes')
                    ->seedTables([
                        'attributes',
                        'attribute_groups',
                        'attribute_options',
                        'attribute_category',
                    ]),
                CreateAttributeAction::make()
                    ->visible($this->roleOrPermission(['create', 'manage'], 'attribute')),
            ])
            ->actions([
                EditAttributeAction::make()
                    ->visible($this->roleOrPermission(['update', 'manage'], 'attribute')),
                    
                DeleteAction::make('delete')
                    ->hiddenLabel()
                    ->button()
                    ->visible($this->roleOrPermission(['delete', 'manage'], 'attribute')),
            ])
            ->recordAction('edit')
            ->bulkActions([
                ForceDeleteBulkAction::make()
                    ->visible($this->roleOrPermission(['forceDelete', 'manage'], 'attribute')),
                ExportBulkAction::make()
                    ->exporter(AttributeExporter::class),
            ])
            ->paginated(false)
            ->persistFiltersInSession()
            ->filters([
                SelectFilter::make('category')
                    ->options(Category::all()->groupBy('parent.name')->map->pluck('name', 'id'))
                    ->query(fn ($query, $data) => 
                        $query->when($data['value'], fn ($query) => $query->whereHas('categories', fn ($query) => $query->where('category_id', $data['value'])))
                    )
            ]);
    }
}
