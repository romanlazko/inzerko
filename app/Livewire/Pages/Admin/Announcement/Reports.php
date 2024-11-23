<?php 

namespace App\Livewire\Pages\Admin\Announcement;

use App\Livewire\Layouts\AdminTableLayout;
use App\Models\Announcement;
use App\Models\Report;
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
    
    public Announcement $announcement;

    public function mount($announcement_id)
    {
        $this->announcement = Announcement::find($announcement_id);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading("Reports")
            ->query($this->announcement->reports()->orderByDesc('id')->getQuery())
            ->columns([
                TextColumn::make('id')->sortable(),
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
            ->poll('2s');
    }
}