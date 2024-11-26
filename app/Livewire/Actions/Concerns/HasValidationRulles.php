<?php

namespace App\Livewire\Actions\Concerns;

use Closure;

trait HasValidationRulles 
{
    public $validation_rules = [
        'numeric' => 'Только число', 
        'string' => 'Только строка',
        'email' => 'Email',
        'phone' => 'Phone',
    ];

    public function getValidationRules(): array
    {
        return [
            fn () => function (string $attribute, $value, Closure $fail) {
                if (!isset($value['en']) OR $value['en'] == '') {
                    $fail('The :attribute must contain english translation.');
                }
            }
        ];
    }
}