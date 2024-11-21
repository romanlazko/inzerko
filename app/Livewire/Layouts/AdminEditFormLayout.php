<?php

namespace App\Livewire\Layouts;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Filament\Forms\Form;

abstract class AdminEditFormLayout extends AdminLayout implements HasForms
{
    use InteractsWithForms;
    
    public ?array $data = [];
    
    abstract public function form(Form $form): Form;

    abstract public function update();

    public function render(): View
    {
        abort_if(! $this->roleOrPermission(['admin', 'moderator', 'super-duper-admin']), 403);

        return view('livewire.layouts.edit-form');
    }
}
