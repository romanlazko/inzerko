<?php
namespace App\Bots\inzerko_bot\Commands;

use Romanlazko\Telegram\App\Commands\CommandsList as DefaultCommandsList;

class CommandsList extends DefaultCommandsList
{
    static protected $commands = [
        'admin'     => [
            AdminCommands\StartCommand::class,
        ],
        'user'      => [
            UserCommands\DefaultCommand::class,
            UserCommands\GetContact::class,
            UserCommands\ConnectCommand::class,

            UserCommands\CreateAnnouncement::class,

            UserCommands\Profile\Profile::class,
            UserCommands\Profile\StoreProfile::class,
            UserCommands\Profile\UpdateProfile::class,

            UserCommands\Profile\Email::class,
            UserCommands\Profile\AwaitEmail::class,

            UserCommands\Profile\Phone::class,
            UserCommands\Profile\AwaitPhone::class,

            UserCommands\Profile\Languages::class,
            UserCommands\Profile\AwaitLanguages::class,

            UserCommands\SendTelegramEmailVerificationNotification::class,
            UserCommands\SendVerifyTelegramConnection::class,

            UserCommands\StartCommand::class,
            UserCommands\MenuCommand::class,
        ],
    ];

    static public function getCommandsList(?string $auth)
    {
        return array_merge(
            (self::$commands[$auth] ?? []), 
            (self::$default_commands[$auth] ?? [])
        ) 
        ?? self::getCommandsList('default');
    }

    static public function getAllCommandsList()
    {
        foreach (self::$commands as $auth => $commands) {
            $commands_list[$auth] = self::getCommandsList($auth);
        }
        return $commands_list;
    }
}
