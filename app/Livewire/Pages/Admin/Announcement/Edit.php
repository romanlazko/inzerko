<?php

namespace App\Livewire\Pages\Admin\Announcement;

use App\AttributeType\AttributeFactory;
use App\Livewire\Components\Forms\Fields\Wizard;
use App\Livewire\Layouts\AdminEditFormLayout;
use App\Livewire\Traits\AnnouncementCrud;
use App\Models\Announcement;
use App\Models\Category;
use App\Services\Actions\AttributesByCategoryService;
use App\Services\AnnouncementService;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class Edit extends AdminEditFormLayout
{
    public ?array $data = [
    ];

    public $categories;

    public Announcement $announcement;

    public function mount(Announcement $announcement): void
    {
        $this->categories = Category::all();
        $this->form->fill($this->setData($announcement));
    }

    public function render(): View
    {
        return view('livewire.components.announcement.edit');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Actions::make([
                    Action::make('reset_form')
                        ->label(__('livewire.labels.reset_form'))
                        ->color('danger')
                        ->link()
                        ->icon('heroicon-m-x-mark')
                        ->action(fn () => $this->resetData()),
                ]),
                Wizard::make([
                    Step::make('categories')
                        ->schema([
                            Section::make(__('livewire.category'))
                                ->schema([
                                    SelectTree::make('category_id')
                                        ->label(__('livewire.category'))
                                        ->relationship('category', 'name', 'parent_id')
                                        ->placeholder(__('Please select a category'))
                                        ->required()
                                        ->live()
                                ]),
                        ])
                        ->extraAttributes(['style' => 'padding: 0; margin: 0; gap: 0px;']),
                    Step::make('features')
                        ->schema(fn (Get $get) => $this->getSections($get('category_id')))
                        ->extraAttributes(['style' => 'padding: 0; margin: 0; gap: 0px;']),
                    Step::make('photos')
                        ->visible(fn (Get $get) => $this->categories->find($get('category_id'))?->has_attachments ?? false)
                        ->schema([
                            Section::make(__('livewire.photos'))
                                ->schema([
                                    SpatieMediaLibraryFileUpload::make('attachments')
                                        ->collection('announcements')
                                        ->hiddenLabel()
                                        ->multiple()
                                        ->image()
                                        ->imagePreviewHeight('120')
                                        ->required(),
                                ]),
                        ])
                        ->extraAttributes(['style' => 'padding: 0; margin: 0; gap: 0px;']),
                ])
                ->submitAction(new HtmlString(Blade::render(<<<BLADE
                    <x-filament.submit>
                        {{ __('livewire.update') }}
                    </x-filament.submit>
                BLADE)))
                ->contained(false)
            ])
            
            ->statePath('data')
            ->model($this->announcement);
    }

    public function update(): void
    {
        $this->validate();

        $announcement = AnnouncementService::update($this->announcement, (object) $this->form->getState());

        $this->form->model($announcement)->saveRelationships();

        $this->afterUpdating();
    }

    public function afterUpdating()
    {
        $this->redirectRoute('admin.announcement.announcements');
    }

    public function getSections($category_id): array
    {
        return AttributesByCategoryService::forUpdate($this->categories->find($category_id))
            ?->sortBy('createSection.order_number')
            ?->groupBy('createSection.name')
            ?->map(function ($section, $section_name) {
                $fields = $this->getFields($section);
                
                if ($fields->isNotEmpty()) {
                    return Section::make($section_name)
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
        return $section
            ->sortBy('create_layout->order_number')
            ->map(fn ($attribute) =>
                AttributeFactory::getCreateComponent($attribute)
            )
            ->filter();
    }

    private function resetData()
    {
        $this->dispatch('reset-form');
        $this->reset('data');
        $this->form->fill($this->data);
    }

    private function setData($announcement)
    {
        $data = [
            'geo_id' => $announcement->geo_id,
            'category_id' => $announcement->category_id,
        ];

        foreach ($announcement->features as $feature) {
            $data['attributes'][$feature->attribute->name] = $feature->original;
        }

        return $data;
    }
}