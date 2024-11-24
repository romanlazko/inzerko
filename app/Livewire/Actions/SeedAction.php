<?php
namespace App\Livewire\Actions;

use Carbon\Carbon;
use Filament\Tables\Actions\Action;
use Orangehill\Iseed\Facades\Iseed;

class SeedAction extends Action
{
    protected array $seedTables = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label("Seed {$this->name}: ". $this->getFileCTime($this->name))
            ->action(function () {
                foreach ($this->seedTables as $table_name) {
                    Iseed::generateSeed($table_name);
                }

                $this->label("Seed {$this->name}: ". $this->getFileCTime($this->name));
            })
            ->icon('heroicon-o-circle-stack')
            ->hidden(app()->environment('production'));
    }
    

    public function seedTables(array $seedTables)
    {
        $this->seedTables = $seedTables;

        return $this;
    }

    protected function getFileCTime($table)
    {
        $className = Iseed::generateClassName($table);

        // Get a seed folder path
        $seedPath = Iseed::getSeedPath();

        // Get a app/database/seeds path
        $seedsPath = Iseed::getPath($className, $seedPath);

        if (!file_exists($seedsPath)) {
            return null;
        }

        $creationTime = filectime($seedsPath);
    
        // Проверка и форматирование даты
        if ($creationTime !== false) {
            return Carbon::parse($creationTime)->diffForHumans();
        } 
        
        return null;
    }
}