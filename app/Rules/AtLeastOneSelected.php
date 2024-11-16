<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AtLeastOneSelected implements ValidationRule
{
    public function __construct(public string $attribute_name) {

    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->passes($value)) {
            $fail(__('validation.at_least_one_selected'));
        }
    }

    public function passes($value)
    {
        foreach ($value as $item) {
            if (isset($item[$this->attribute_name])) {
                return true;
            }
        }

        return false;
    }
}
