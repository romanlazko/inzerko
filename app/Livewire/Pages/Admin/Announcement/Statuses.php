<?php

namespace App\Livewire\Pages\Admin\Announcement;

use App\Livewire\Pages\Layouts\AdminLayout;
use App\Models\Announcement;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use OwenIt\Auditing\Models\Audit;
use PepperFM\FilamentJson\Columns\JsonColumn;
use App\Models\Status;

class Statuses extends AdminLayout implements HasForms, HasTable
{
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
