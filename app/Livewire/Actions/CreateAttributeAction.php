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
use App\Livewire\Actions\Concerns\AttributeSectionFormSection;
use App\Models\Attribute;
use Filament\Tables\Actions\CreateAction;

class CreateAttributeAction extends CreateAction
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

    public static function make(?string $name = null): static
    {
        return parent::make($name)
            ->model(Attribute::class)
            ->icon('heroicon-o-plus-circle')
            ->form([
                self::getCategorySection(),

                self::getNameSection(),

                self::getCreateLayoutSection(),

                self::getFilterLayoutSection(),

                self::getShowLayoutSection(),

                self::getGroupSection(),

                self::getOptionsSection(),

                self::getVisibleHiddenSection(),
            ])
            ->slideOver()
            ->extraModalWindowAttributes(['style' => 'background-color: #e5e7eb'])
            ->closeModalByClickingAway(false);
    }
}