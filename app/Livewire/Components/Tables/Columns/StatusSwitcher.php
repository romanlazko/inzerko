<?php

namespace App\Livewire\Components\Tables\Columns;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\Concerns\HasColor;
use Filament\Tables\Columns\SelectColumn;

class StatusSwitcher extends SelectColumn
{
    use HasColor;

    protected string $view = 'livewire.components.tables.columns.status-switcher';
}
