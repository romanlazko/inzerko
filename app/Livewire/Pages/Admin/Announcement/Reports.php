<?php 

namespace App\Livewire\Pages\Admin\Announcement;

use App\Livewire\Layouts\AdminTableLayout;
use App\Models\Announcement;
use App\Models\Report;
use App\Models\Feature;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class Reports extends AdminTableLayout implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public $announcement;

    public function mount($announcement_id = null)
    {
        $this->announcement = Announcement::find($announcement_id);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading("Reports")
            ->query($this->announcement?->reports()->orderByDesc('id')->getQuery() ?? Report::whereHas('reportable')->orderByDesc('id'))
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('announcement.features')
                    ->state(fn (Report $report) => $report->reportable?->features
                        ->groupBy('attribute.createSection.order_number')
                        ->map
                        ->sortBy('attribute.create_layout.order_number')
                        ->flatten()
                        ->map(fn (Feature $feature) => "{$feature->label}: ". str($feature->value)->stripTags()->limit(100))
                    )
                    ->color('neutral')
                    ->badge()
                    ->searchable(query: function ($query, string $search) {
                        return $query
                            ->whereRaw('LOWER(slug) LIKE ?', ['%' . mb_strtolower($search) . '%'])
                            ->orWhereHas('features', fn ($query) => 
                                $query->whereRaw('LOWER(translated_value) LIKE ?', ['%' . mb_strtolower($search) . '%'])
                                    ->orWhereHas('attribute_option', fn ($query) => 
                                        $query->whereRaw('LOWER(alternames) LIKE ?', ['%' . mb_strtolower($search) . '%'])
                                    )
                                );
                    })
                    ->visible(!$this->announcement),
                TextColumn::make('reporter.name')
                    ->description(fn (Report $report) => $report->reporter?->email),
                TextColumn::make('report_option.name')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('description')
                    ->badge(),
                TextColumn::make('created_at')
                    ->since()
            ])
            ->actions([
                
            ]);
    }
}