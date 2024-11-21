<?php

namespace App\Livewire\Pages\Admin\Announcement;

use App\Livewire\Layouts\AdminTableLayout;
use App\Models\Announcement;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\Status;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class Statuses extends AdminTableLayout implements HasForms, HasTable
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
            ->heading("Statuses")
            ->query($this->announcement->statuses()->orderByDesc('id')->getQuery())
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (Status $status) => $status->status->filamentColor()),
                TextColumn::make('info.message')
                    ->action(fn (Status $status) =>
                        dump($status->info)
                    )
                    ->badge(),
                TextColumn::make('created_at')
                    ->since()
            ])
            ->paginationPageOptions([25, 50, 100])
            ->poll('2s');
    }
}
