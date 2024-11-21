<?php

namespace App\Livewire\Layouts;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Attributes\Layout;

abstract class AdminTableLayout extends AdminLayout 
{

    public function render()
    {
        abort_if(! $this->roleOrPermission(['admin', 'moderator', 'super-duper-admin']), 403);

        return view('livewire.layouts.table');
    }
}