<?php

namespace App\Livewire\Actions\Concerns;

trait HasValidationRulles 
{
    public static $validation_rules = [
        'numeric' => 'Только число', 
        'string' => 'Только строка',
        'email' => 'Email',
        'phone' => 'Phone',
    ];
}