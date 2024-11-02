<?php
namespace App\Bots\inzerko_bot\Commands;

use Romanlazko\Telegram\App\Commands\CommandsList as DefaultCommandsList;

class CommandsList extends DefaultCommandsList
{
    static protected $commands = [
        'admin'     => [
            AdminCommands\StartCommand::class,
            AdminCommands\HelpCommand::class,
        ],
        'user'      => [
            UserCommands\GetContact::class,
            UserCommands\ConnectCommand::class,

            UserCommands\CreateAnnouncement::class,

            UserCommands\Profile::class,
            UserCommands\StoreProfile::class,
            UserCommands\UpdateProfile::class,

            UserCommands\Email::class,
            UserCommands\AwaitEmail::class,

            UserCommands\Phone::class,
            UserCommands\AwaitPhone::class,


            UserCommands\SendTelegramEmailVerificationNotification::class,

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
