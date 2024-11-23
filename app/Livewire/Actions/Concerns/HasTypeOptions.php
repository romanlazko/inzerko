<?php

namespace App\Livewire\Actions\Concerns;

trait HasTypeOptions 
{
    public array $type_options = [
        'fields_with_options' => [
            'select' => 'SELECT (выбор одного элемента из списка)',
            'multiple_select' => 'MULTIPLE SELECT (выбор нескольких элементов из списка)',
            'toggle_buttons' => 'TOGGLE BUTTONS (выбор переключателей)',
            'checkbox_list'   => 'CHECKBOX LIST (список чекбоксов)',
            'price' => 'PRICE (цена)',
        ],
        'text_fields' => [
            'text_input' => 'Текстовое поле',
            'text_area' => 'Текстовый блок',
            'between'   => 'Между',
            'from_to'   => 'From-To',
            'markdown_editor' => 'Markdown Editor',
            'rich_editor' => 'Rich Editor',
        ],
        'other' => [
            'location'  => 'Местоположение',
            'hidden'    => 'Скрытое поле',
            'created_at' => 'Дата создания',
        ],
        'date' => [
            'date_picker' => 'Date',
            'date_time_picker' => 'Date Time',
            'month_year' => 'Month Year',
        ],
    ];
}