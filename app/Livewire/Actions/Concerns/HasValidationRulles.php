<?php

namespace App\Livewire\Actions\Concerns;

trait HasValidationRulles 
{
    public $validation_rules = [
        'numeric' => 'Только число', 
        'string' => 'Только строка',
        'email' => 'Email',
        'phone' => 'Phone',
    ];
}