<?php
namespace App\Bots\pozorbottestbot\Commands;

use Romanlazko\Telegram\App\Commands\CommandsList as DefaultCommandsList;

class CommandsList extends DefaultCommandsList
{
    static protected $commands = [
        'admin'     => [
        ],
        'user'      => [

            UserCommands\ConnectCommand::class,

            UserCommands\CreateAnnouncement::class,

            UserCommands\EditProfile::class,

            UserCommands\Email::class,
            UserCommands\AwaitEmail::class,

            UserCommands\Phone::class,
            UserCommands\AwaitPhone::class,

            UserCommands\SaveProfile::class,

            UserCommands\CreateProfile::class,
            UserCommands\SendTelegramEmailVerificationNotification::class,

            UserCommands\StartCommand::class,
            UserCommands\MenuCommand::class,
            
            
        ],
        'supergroup' => [
        ],
        'default'   => [
        ]
        
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
