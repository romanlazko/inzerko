<?php
namespace App\Livewire\Actions;

use App\Livewire\Actions\Concerns\AttributeOptionsSection;
use App\Livewire\Actions\Concerns\CategorySection;
use App\Livewire\Actions\Concerns\CreateLayoutSection;
use App\Livewire\Actions\Concerns\FilterLayoutSection;
use App\Livewire\Actions\Concerns\GroupSection;
use App\Livewire\Actions\Concerns\NameSection;
use App\Livewire\Actions\Concerns\ShowLayoutSection;
use App\Livewire\Actions\Concerns\VisibleHiddenSection;
use App\Models\Attribute\Attribute;
use Filament\Tables\Actions\CreateAction;

class CreateAttributeAction extends CreateAction
{
    use CategorySection;
    use NameSection;
    use CreateLayoutSection;
    use FilterLayoutSection;
    use ShowLayoutSection;
    use AttributeOptionsSection;
    use GroupSection;
    use VisibleHiddenSection;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->model(Attribute::class)
            ->icon('heroicon-o-plus-circle')
            ->form([
                $this->getCategorySection(),

                $this->getNameSection(),

                $this->getCreateLayoutSection(),

                $this->getFilterLayoutSection(),

                $this->getShowLayoutSection(),

                $this->getGroupSection(),

                $this->getAttributeOptionsSection(),

                $this->getVisibleHiddenSection(),
            ])
            ->slideOver()
            ->extraModalWindowAttributes(['style' => 'background-color: #e5e7eb'])
            ->closeModalByClickingAway(false);
    }
}