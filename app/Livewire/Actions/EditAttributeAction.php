<?php
namespace App\Livewire\Actions;

use App\Livewire\Actions\Concerns\CategorySection;
use App\Livewire\Actions\Concerns\CreateLayoutSection;
use App\Livewire\Actions\Concerns\FilterLayoutSection;
use App\Livewire\Actions\Concerns\GroupSection;
use App\Livewire\Actions\Concerns\HasTypeOptions;
use App\Livewire\Actions\Concerns\HasValidationRulles;
use App\Livewire\Actions\Concerns\NameSection;
use App\Livewire\Actions\Concerns\OptionsSection;
use App\Livewire\Actions\Concerns\ShowLayoutSection;
use App\Livewire\Actions\Concerns\VisibleHiddenSection;
use Filament\Tables\Actions\EditAction;

class EditAttributeAction extends EditAction
{
    use CategorySection;
    use NameSection;
    use CreateLayoutSection;
    use HasTypeOptions;
    use HasValidationRulles;
    use FilterLayoutSection;
    use ShowLayoutSection;
    use OptionsSection;
    use GroupSection;
    use VisibleHiddenSection;

    // public static function make(?string $name = null): static
    // {
    //     return parent::make($name)
    //         ->modalHeading(fn ($record) => $record->label)
    //         ->modalDescription(fn ($record) => $record->name)
    //         ->form([
    //             self::getCategorySection(),

    //             self::getNameSection(),

    //             self::getCreateLayoutSection(),

    //             self::getFilterLayoutSection(),

    //             self::getShowLayoutSection(),

    //             self::getGroupSection(),

    //             self::getOptionsSection(),

    //             self::getVisibleHiddenSection(),
    //         ])
    //         ->hiddenLabel()
    //         ->button()
    //         ->slideOver()
    //         ->extraModalWindowAttributes(['style' => 'background-color: #e5e7eb'])
    //         ->closeModalByClickingAway(false);
    // }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->modalHeading(fn ($record) => $record->label)
            ->modalDescription(fn ($record) => $record->name)
            ->form([
                $this->getCategorySection($this->type_options, $this->validation_rules),

                $this->getNameSection($this->type_options, $this->validation_rules),

                $this->getCreateLayoutSection($this->type_options, $this->validation_rules),

                $this->getFilterLayoutSection($this->type_options, $this->validation_rules),

                $this->getShowLayoutSection($this->type_options, $this->validation_rules),

                $this->getGroupSection($this->type_options, $this->validation_rules),

                $this->getOptionsSection($this->type_options, $this->validation_rules),

                $this->getVisibleHiddenSection($this->type_options, $this->validation_rules),
            ])
            ->hiddenLabel()
            ->button()
            ->slideOver()
            ->extraModalWindowAttributes(['style' => 'background-color: #e5e7eb'])
            ->closeModalByClickingAway(false);
    }
}