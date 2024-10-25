<?php

namespace App\Livewire\Pages\Layouts;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Livewire\Attributes\Layout;
use Livewire\Component;

abstract class AdminLayout extends Component
{
    use InteractsWithTable;
    use InteractsWithForms;

    #[Layout('layouts.admin')]

    public function render()
    {
        return view('livewire.admin.table');
    }
}