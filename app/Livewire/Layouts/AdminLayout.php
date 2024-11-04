<?php

namespace App\Livewire\Layouts;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Livewire\Attributes\Layout;
use Livewire\Component;

abstract class AdminLayout extends Component
{
    #[Layout('layouts.admin')]

    public function render()
    {
        return view('livewire.layouts.layout');
    }
}