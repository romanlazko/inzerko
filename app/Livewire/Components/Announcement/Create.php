<?php

namespace App\Livewire\Components\Announcement;

use App\AttributeType\AttributeFactory;
use App\Livewire\Components\Forms\Fields\Wizard;
use App\Livewire\Traits\AnnouncementCrud;
use App\Models\Announcement;
use App\Models\Category;
use App\Services\Actions\CategoryAttributeService;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;

class Create extends Component implements HasForms
{
    use InteractsWithForms, AnnouncementCrud;

    public ?array $data = [
        'attachments' => [],
        'category_id' => null,
        'geo_id' => null,
        'attributes' => [
            'description' => '',
        ],
    ];

    public $categories;
    
    public function mount(): void
    {
        $this->categories = Category::all();
        $this->form->fill(session('create_data', $this->data));
    }

    public function render(): View
    {
        session()->put('create_data', array_diff_key($this->data, ['attachments' => ""]));

        return view('livewire.components.announcement.create');
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
                        {{ __('livewire.create') }}
                    </x-filament.submit>
                BLADE)))
                ->contained(false)
            ])
            ->model(Announcement::class)
            ->statePath('data');
    }

    public function create(): void
    {
        $this->validate();

        $announcement = $this->createAnnouncement((object) $this->form->getState());

        $this->form->model($announcement)->saveRelationships();

        session()->forget('create_data');

        $this->afterCreating();
    }

    public function afterCreating()
    {
        $this->redirectRoute('profile.my-announcements');
    }

    public function getSections($category_id): array
    {
        return CategoryAttributeService::forCreate($this->categories->find($category_id))
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
        session()->forget('create_data');
        $this->reset('data');
        $this->form->fill($this->data);
    }
}