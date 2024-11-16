<?php

namespace App\Livewire\Layouts;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;

#[Layout('layouts.admin')]
abstract class AdminEditFormLayout extends Component implements HasForms
{
    use InteractsWithForms;
    
    public ?array $data = [];
    
    abstract public function form(Form $form): Form;

    abstract public function update();

    public function render(): View
    {
        return view('livewire.layouts.edit-form');
    }
}
