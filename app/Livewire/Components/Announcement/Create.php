<?php

namespace App\Livewire\Components\Announcement;

use App\AttributeType\AttributeFactory;
use App\Livewire\Components\Forms\Fields\Wizard;
use App\Livewire\Traits\AnnouncementCrud;
use App\Models\Category;
use App\Services\Actions\CategoryAttributeService;
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
        'geo_id' => null,
        'attachments' => null,
        'attributes' => [
            'description' => '',
            'country' => 'CZ',
        ],
        'categories' => [],
        'category_id' => null
    ];

    public $parent_categories;

    protected $schema = [];

    public $category_attributes = null;

    protected $categories = null;

    public function mount(): void
    {
        $this->form->fill(session('data') ?? $this->data);
        $this->parent_categories = Category::where('parent_id', null)->isActive()->get()->pluck('name', 'id');
    }

    public function render(): View
    {
        session()->put('data', array_diff_key($this->data, ['attachments' => ""]));

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
                Wizard::make(fn (Get $get, Set $set) => [
                    Step::make('categories')
                        ->schema([
                            Section::make(__('livewire.category'))
                                ->schema([
                                    TextInput::make('category_id')
                                        ->hidden()
                                        ->dehydratedWhenHidden()
                                        ->required(),
                                    Grid::make(2)
                                        ->schema(fn (Get $get, Set $set) => [
                                            Select::make('categories.0')
                                                ->options($this->parent_categories)
                                                ->afterStateUpdated(function (Get $get) {
                                                    foreach ($this->data['categories'] as $key => $value) {
                                                        if ($key == 0) continue;
                                                        unset($this->data['categories'][$key]);
                                                    }
                                                })
                                                ->dehydrated(false)
                                                ->required()
                                                ->live(),
                                            ...$this->getSubcategories($get, $set)
                                        ]),
                                ]),
                        ])
                        ->extraAttributes(['style' => 'padding: 0; margin: 0; gap: 0px;']),
                    Step::make('features')
                        ->schema($this->getFormSchema())
                        ->extraAttributes(['style' => 'padding: 0; margin: 0; gap: 0px;']),
                    Step::make('photos')
                        ->visible(Category::find($this->data['category_id'])?->hasPhotos ?? false)
                        ->schema([
                            Section::make(__('livewire.photos'))
                                ->schema([
                                    SpatieMediaLibraryFileUpload::make('attachments')
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
            
            ->statePath('data');
    }

    public function create(): void
    {
        $this->validate();

        $this->createAnnouncement((object) $this->data);

        session()->forget('data');

        $this->afterCreating();
    }

    public function afterCreating()
    {
        $this->redirectRoute('profile.my-announcements');
    }

    public function getFormSchema(): array
    {
        return CategoryAttributeService::forCreate(Category::find($this->data['category_id']))
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

    public function getSubcategories(Get $get, Set $set, int $currentLevel = 0): array
    {
        $currentCategoryChildren = $this->getCategories()?->get($get('categories.'.$currentLevel))?->get('children');
        $nextLevel = $currentLevel + 1;

        if ($currentCategoryChildren?->where('is_active', true)?->isNotEmpty()) {
            return [
                Select::make('categories.'.$nextLevel)
                    ->options($currentCategoryChildren?->pluck('name', 'id'))
                    ->hiddenLabel()
                    ->live()
                    ->afterStateUpdated(function (Set $set) use ($nextLevel) {
                        unset($this->data['categories'][$nextLevel+1]);
                    })
                    ->dehydrated(false)
                    ->required(),
                ...$this->getSubcategories($get, $set, $nextLevel)
            ];
        }
        else {
            $set('category_id', $get('categories.'.$currentLevel));
        }

        return [];
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

    public function getCategories(): SupportCollection
    {
        $cacheKey = implode('_', $this->data['categories'] ?? []) . '_create_categories';

        return Cache::remember($cacheKey, config('cache.ttl'), function () {
            return Category::whereIn('id', $this->data['categories'])
                ->select('id', 'alternames', 'parent_id', 'is_active')
                ->with('children:alternames,id,parent_id,is_active')
                ->isActive()
                ->get()
                ->keyBy('id')
                ->map(fn (Category $category) => collect([
                    'id' => $category->id,
                    'children' => $category->children,
                ]));
        });
    }

    private function resetData()
    {
        $this->dispatch('reset-form');
        session()->forget('data');
        $this->reset('data');
        $this->form->fill($this->data);
    }
}