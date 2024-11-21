<?php

namespace App\Livewire\Pages\Admin\Telegram;

use App\Livewire\Layouts\AdminTableLayout;

use App\Models\Category;
use App\Models\TelegramBot;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;

use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Novadaemon\FilamentPrettyJson\PrettyJson;
use Romanlazko\Telegram\App\Bot;
use Romanlazko\Telegram\Generators\BotDirectoryGenerator;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class Bots extends AdminTableLayout implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->heading("All bots")
            ->query(TelegramBot::query())
            ->columns([
                SpatieMediaLibraryImageColumn::make('avatar')
                    ->collection('bots')
                    ->conversion('thumb'),
                TextColumn::make('first_name')
                    ->sortable()
                    ->grow()
                    ->description(fn ($record) => $record->username),
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(Category::class)
                    ->icon('heroicon-o-plus-circle')
                    ->form([
                        Section::make('Telegram')
                            ->schema([
                                TextInput::make('token')
                            ])
                    ])
                    ->action(function (array $data) {
                        $bot = new Bot($data['token']);

                        $response = $bot::setWebHook([
                            'url' => env('APP_URL').'/api/telegram/'.$bot->getBotId(),
                        ]);

                        if ($response->getOk()) {
                            $telegram_bot = TelegramBot::updateOrCreate([
                                'id'            => $bot->getBotChat()->getId(),
                                'first_name'    => $bot->getBotChat()->getFirstName(),
                                'last_name'     => $bot->getBotChat()->getLastName(),
                                'username'      => $bot->getBotChat()->getUsername(),
                                'token'         => $data['token'],
                            ]);

                            $telegram_bot->addMediaFromUrl($bot->getBotChat()->getPhotoLink())->toMediaCollection('bots');
                        }

                        if ($telegram_bot) {
                            BotDirectoryGenerator::createBotDirectories($telegram_bot->username);
                        }

                        return $response->getDescription();
                    })  
                    ->slideOver()
                    ->closeModalByClickingAway(false)
                    ->visible($this->roleOrPermission(['create', 'manage'], 'telegram')),
            ])
            ->recordAction('view')
            ->actions([
                ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->hiddenLabel()
                    ->button()
                    ->color('warning')
                    ->form([
                        TextInput::make('token'),
                        PrettyJson::make('webhook')
                            ->formatStateUsing(function ($record) {
                                $bot = new Bot($record->token);
                                return json_encode($bot::getWebhookInfo()->getResult());
                            }),
                        PrettyJson::make('commands_list')
                            ->formatStateUsing(function ($record) {
                                $bot = new Bot($record->token);
                                return json_encode($bot->getAllCommandsList());
                            }),
                    ])
                    ->visible($this->roleOrPermission(['view', 'manage'], 'telegram')),
                DeleteAction::make()
                    ->visible($this->roleOrPermission(['delete', 'manage'], 'telegram'))
                    ->hiddenLabel()
                    ->button(),
            ]);
    }
}
