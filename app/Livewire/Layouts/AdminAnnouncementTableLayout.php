<?php

namespace App\Livewire\Layouts;

use App\Livewire\Pages\Admin\Announcement\Audits;
use App\Livewire\Pages\Admin\Announcement\Channels;
use App\Livewire\Pages\Admin\Announcement\Statuses;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use App\Models\Announcement;
use App\Models\Category;
use Filament\Support\Enums\ActionSize;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Filters\Filter;
use CodeWithDennis\FilamentSelectTree\SelectTree;

abstract class AdminAnnouncementTableLayout extends AdminTableLayout
{
    protected function createStatusActions(): ActionGroup
    {
        return ActionGroup::make([
            Action::make('send_to_moderation')
                ->label(__("Send to moderation"))
                ->action(fn (Announcement $announcement) => $announcement->moderate())
                ->color('info')
                ->button()
                ->icon('heroicon-m-arrow-right-circle')
                ->size(ActionSize::ExtraSmall),
        ])
        ->dropdown(false)
        ->visible(fn (Announcement $announcement) => 
            $announcement->status?->isCreated()
        );
    }

    protected function moderationStatusActions(): ActionGroup
    {
        return ActionGroup::make([
            Action::make('approve')
                ->label(__("Approve"))
                ->action(fn (Announcement $announcement) => $announcement->approve())
                ->color('info')
                ->button()
                ->icon('heroicon-s-check-circle')
                ->size(ActionSize::ExtraSmall),

            Action::make('reject')
                ->label(__("Reject"))
                ->form([
                    Section::make()
                        ->schema([
                            Textarea::make('info')
                                ->label(__("Reason"))
                                ->required()
                                ->rows(6),
                        ])
                ])
                ->action(fn (array $data, Announcement $announcement) => $announcement->reject($data))
                ->color('danger')
                ->button()
                ->icon('heroicon-c-no-symbol')
                ->slideOver()
                ->modalWidth('md')
                ->extraModalWindowAttributes(['style' => 'background-color: #e5e7eb'])
                ->size(ActionSize::ExtraSmall),
        ])
        ->dropdown(false)
        ->visible(fn (Announcement $announcement) => 
            $announcement->status?->isOnModeration()
        );
    }

    protected function publicationStatusActions(): ActionGroup
    {
        return ActionGroup::make([
            Action::make('stop_publishing')
                ->label(__("Stop"))
                ->action(fn (Announcement $announcement) => $announcement->publishingFailed())
                ->color('danger')
                ->button()
                ->icon('heroicon-c-no-symbol')
                ->size(ActionSize::ExtraSmall)
                ->visible(fn (Announcement $announcement) => 
                    $announcement->status?->isAwaitPublication()
                ),

            Action::make('retry_publication')
                ->label(__("Retry"))
                ->action(fn (Announcement $announcement) => $announcement->publish())
                ->color('warning')
                ->button()
                ->icon('heroicon-c-arrow-path-rounded-square')
                ->size(ActionSize::ExtraSmall)
                ->visible(fn (Announcement $announcement) => 
                    $announcement->status?->isPublishingFailed()
                ),
            ])
            ->dropdown(false);
    }

    protected function translateStatusActions(): ActionGroup
    {
        return ActionGroup::make([
            Action::make('translate')
                ->label(__("Translate"))
                ->action(fn (Announcement $announcement) => $announcement->translate())
                ->color('info')
                ->button()
                ->icon('heroicon-c-language')
                ->size(ActionSize::ExtraSmall)
                ->visible(fn (Announcement $announcement) => $announcement->status?->isApproved()),
                
            Action::make('publish_without_translating')
                ->label(__("Publish without translating"))
                ->action(fn (Announcement $announcement) => $announcement->publish())
                ->color('warning')
                ->button()
                ->icon('heroicon-c-no-symbol')
                ->size(ActionSize::ExtraSmall),

            Action::make('stop_translating')
                ->label(__("Stop"))
                ->action(fn (Announcement $announcement) => $announcement->translationFailed())
                ->color('danger')
                ->button()
                ->icon('heroicon-c-no-symbol')
                ->size(ActionSize::ExtraSmall)
                ->visible(fn (Announcement $announcement) => $announcement->status?->isAwaitTranslation()),
            
            Action::make('retry_translation')
                ->label(__("Retry"))
                ->action(fn (Announcement $announcement) => $announcement->translate())
                ->color('warning')
                ->button()
                ->icon('heroicon-c-arrow-path-rounded-square')
                ->size(ActionSize::ExtraSmall)
                ->visible(fn (Announcement $announcement) => $announcement->status?->isTranslationFailed()),
        ])
        ->dropdown(false)
        ->visible(fn (Announcement $announcement) => 
            $announcement->status?->isApproved() OR $announcement->status?->isAwaitTranslation() OR $announcement->status?->isTranslationFailed()
        );
    }

    protected function defaultActions(): ActionGroup
    {
        return ActionGroup::make([
            Action::make('history')
                ->label(__("View history"))
                ->form(fn (Announcement $announcement) => [
                    Livewire::make(Audits::class, ['announcement_id' => $announcement->id])
                ])
                ->hiddenLabel()
                ->extraModalWindowAttributes(['style' => 'background-color: #e5e7eb'])
                ->modalSubmitAction(false)
                ->slideover()
                ->modalWidth('7xl')
                ->button()
                ->icon('heroicon-o-clock')
                ->size(ActionSize::ExtraSmall),
            
            Action::make('statuses')
                ->label(__("View statuses"))
                ->form(fn (Announcement $announcement) => [
                    Livewire::make(Statuses::class, ['announcement_id' => $announcement->id])
                ])
                ->extraModalWindowAttributes(['style' => 'background-color: #e5e7eb'])
                ->icon('heroicon-m-square-3-stack-3d')
                ->slideover()
                ->modalWidth('7xl')
                ->hiddenLabel()
                ->color('info')
                ->button()
                ->size(ActionSize::ExtraSmall),

            Action::make("Telegram Channels")
                ->modalHeading(fn (Announcement $announcement) => "Telegram Channels: {$announcement->getFeatureByName('title')?->value}")
                ->form(fn (Announcement $announcement) => [
                    Livewire::make(Channels::class, ['announcement_id' => $announcement->id]),
                ])
                ->extraModalWindowAttributes(['style' => 'background-color: #e5e7eb'])
                ->icon('heroicon-o-megaphone')
                ->slideover()
                ->modalWidth('7xl')
                ->hiddenLabel()
                ->color('info')
                ->button()
                ->size(ActionSize::ExtraSmall),

            DeleteAction::make('delete_announcement')
                ->hiddenLabel()
                ->button()
                ->size(ActionSize::ExtraSmall),
        ])
        ->dropdown(false);
    }

    protected function filters(): array
    {
        return [
            Filter::make('current_status')
                ->form([
                    Select::make('current_status')
                        ->options(fn () => 
                            Announcement::select('current_status', DB::raw('count(id) as count'))
                                ->groupBy('current_status')
                                ->get()
                                ->mapWithKeys(function ($announcement_status) {
                                    return [$announcement_status->current_status->value => $announcement_status->current_status->getLabel() . ' (' . $announcement_status->count . ')'];
                                })
                                ->toArray()
                        ),
                ])
                ->query(function ($query, array $data) {
                    return $query->when($data['current_status'], fn ($query) => $query->where('current_status', $data['current_status']));
                }),

            // Filter::make('category')
            //     ->form([
            //         Select::make('category')
            //             ->options(fn () => 
            //                 Category::all()
            //                     ->loadCount('announcements')
            //                     ->groupBy('parent.name')
            //                     ->map
            //                     ->mapWithKeys(fn ($category) => [$category->id => $category->name . ' (' . $category->announcements_count . ')'])
            //                     ->toArray()
            //             ),
            //     ])
            //     ->query(function ($query, array $data) {
            //         return $query->when($data['category'], fn ($query) => $query->category(Category::find($data['category'])));
            //     }),
            Filter::make('tree')
                ->form([
                    SelectTree::make('category')
                        ->relationship('category', 'name', 'parent_id'),
                ])
                ->query(function ($query, array $data) {
                    return $query->when($data['category'], fn ($query) => $query->category(Category::find($data['category'])));
                }),
                // ->query(function ($query, array $data) {
                //     return $query->when($data['category'], function ($query, $categories) {
                //         return $query->whereHas('category', fn($query) => $query->where('id', $categories));
                //     });
                // }),
                // ->indicateUsing(function (array $data): ?string {
                //     if (! $data['category']) {
                //         return null;
                //     }
        
                //     return __('Categories') . ': ' . implode(', ', Category::where('id', $data['category'])->get()->pluck('name')->toArray());
                // }),

            SelectFilter::make('user')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
        ];
    }
}