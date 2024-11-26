<?php

namespace App\Livewire\Components\Announcement;

use App\AttributeType\AttributeFactory;
use App\Models\Attribute\Attribute;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use App\Models\Category;
use App\Services\Actions\AttributesByCategoryService;
use Filament\Forms\Components\Fieldset;

class Filters extends Component implements HasForms
{
    use InteractsWithForms;

    public $filters = [];

    public $category;

    public $count = 0;

    public function mount(null|array $filters = null , $category = null)
    {
        $this->category = $category;

        $this->form->fill($filters);
    }
    
    public function render()
    {
        return view('livewire.components.announcement.filters');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Actions::make([
                    Action::make('reset')
                        ->icon('heroicon-m-x-mark')
                        ->action(fn () => $this->resetData())
                        ->label(__('livewire.reset_filters'))
                        ->link()
                        ->color('danger')
                ]),
                Grid::make()
                    ->schema($this->getFormSchema())
            ])
            ->statePath('filters')
            ->extraAttributes(['class' => 'rounded-2xl']);
    }

    public function search()
    {
        return $this->redirectRoute('announcement.search', ['category' => $this->category?->slug, 'filters' => $this->filters]);
    }

    public function getFormSchema(): array
    {
        return AttributesByCategoryService::forFilter($this->category)
            ?->sortBy('filterSection.order_number')
            ?->groupBy('filterSection.name')
            ?->map(function ($section, $section_name) {
                $fields = $this->getFields($section);
                
                if ($fields->isNotEmpty()) {
                    return Fieldset::make($section_name)
                        ->extraAttributes([
                            'class' => 'bg-white',
                        ])
                        ->schema([
                            Grid::make([
                                'default' => 2,
                                'sm' => 4,
                                'md' => 4,
                                'lg' => 4,
                                'xl' => 4,
                                '2xl' => 4,
                            ])
                            ->schema($fields->toArray())
                        ]);
                }
            })
            ?->filter()
            ?->toArray();
    }

    public function getFields($section)
    {
        return $section->sortBy('filter_layout.order_number')->map(function (Attribute $attribute) {
            return AttributeFactory::getFilterComponent($attribute);
        })
        ->filter();
    }

    private function resetData()
    {
        session()->forget('filters');
        $this->reset('filters');
        $this->form->fill($this->filters);
        $this->search();
    }
}

