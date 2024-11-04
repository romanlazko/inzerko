<?php
namespace App\Bots\pozor_baraholka_bot\Commands;

use Romanlazko\Telegram\App\Commands\CommandsList as DefaultCommandsList;

class CommandsList extends DefaultCommandsList
{
    static private $commands = [
        'admin'     => [
            AdminCommands\StartCommand::class,
            AdminCommands\MenuCommand::class,
            AdminCommands\ShowAnnouncement::class,
            AdminCommands\PublicAnnouncement::class,
            UserCommands\GetOwnerContact::class,
            AdminCommands\RejectAnnouncement::class,
            AdminCommands\ConfirmReject::class,
        ],
        'user'      => [
            UserCommands\StartCommand::class,
            UserCommands\MenuCommand::class,
            
            UserCommands\GetOwnerContact::class,
        ],
        'supergroup' => [
        ],
        'default'   => [
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
