<?php

namespace App\Livewire\Pages\Admin\Telegram;

use App\Livewire\Pages\Layouts\AdminLayout;
use App\Models\Category;
use App\Models\TelegramBot;
use Faker\Provider\ar_EG\Text;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Novadaemon\FilamentPrettyJson\PrettyJson;
use Romanlazko\Telegram\App\Bot;
use Romanlazko\Telegram\Generators\BotDirectoryGenerator;

class Bots extends AdminLayout implements HasForms, HasTable
{
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
                    ->closeModalByClickingAway(false),
            ])
            ->recordAction('view')
            ->actions([
                Action::make('Chats')
                    ->hiddenLabel()
                    ->button()
                    ->icon('heroicon-o-chat-bubble-bottom-center')
                    ->url(fn ($record) => route('admin.telegram.chats', $record))
                    ->color('success'),
                Action::make('Channels')
                    ->hiddenLabel()
                    ->button()
                    ->icon('heroicon-o-megaphone')
                    ->url(fn ($record) => route('admin.telegram.channels', $record))
                    ->color('info'),

                Action::make('Logs')
                    ->hiddenLabel()
                    ->button()
                    ->icon('heroicon-o-clipboard-document-list')
                    ->url(fn ($record) => route('admin.telegram.logs', $record))
                    ->color('danger'),

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
                    ]),
            ]);
    }
}
