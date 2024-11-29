<?php

namespace App\Livewire\Actions;

use App\Models\Announcement;
use App\Models\ReportOption;
use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;

class SendReport extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public $announcement_id;

    public function render()
    {
        $actions = [
            'sendReport',
        ];

        return view('livewire.layouts.actions', compact('actions'));
    }

    public function sendReport()
    {
        if (! $announcement = Announcement::find($this->announcement_id)) {
            return Action::make('sendMessage')
                ->hidden()
                ->extraAttributes([
                    'class' => 'hidden',
                ]);
        }

        $action = Action::make('sendReport')
            ->color('warning')
            ->modalHeading(__('livewire.report'))
            ->icon('heroicon-o-exclamation-triangle')
            ->hiddenLabel()
            ->link()
            ->modalWidth('xl');

        if (auth()->guest()) {
            return $action
                ->requiresConfirmation()
                ->modalHeading(__('livewire.should_be_loggined'))
                ->modalDescription('')
                ->extraModalFooterActions([
                    Action::make('login')
                        ->label(__('livewire.login'))
                        ->color('primary')
                        ->action(fn () => redirect(route('login')))
                ])
                ->modalSubmitAction(false)
                ->modalCancelAction(false);
        }

        if ($announcement->user->id == auth()->id()) {
            return $action
                ->modalHeading(__('livewire.you_cant_report_yourself'))
                ->requiresConfirmation()
                ->modalDescription('')
                ->modalSubmitAction(false);
        }

        return $action
            ->form([
                ToggleButtons::make('report_option_id')
                    ->hiddenLabel()
                    ->label(__('livewire.labels.report_reason'))
                    ->required()
                    ->options(ReportOption::where('is_active', true)->orderBy('order_number')->get()->pluck('name', 'id')),
                Textarea::make('descpirtion')
                    ->hiddenLabel()
                    ->placeholder(__('livewire.placeholders.report_description'))
                    ->required()
                    ->rules(['max:255'])
                    ->rows(5),
            ])
            ->action(function (array $data) use ($announcement) {
                $announcement->reports()->create([
                    'reporter_id' => auth()->user()->id,
                    'report_option_id' => $data['report_option_id'],
                    'description' => $data['descpirtion'],
                ]);
            });
    }
}