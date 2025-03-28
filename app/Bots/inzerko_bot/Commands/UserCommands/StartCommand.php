<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class StartCommand extends Command
{
    public static $command = 'start';

    public static $usage = ['/start', 'start'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->clear();
        
        return $this->bot->executeCommand(MenuCommand::$command);
    }
}
