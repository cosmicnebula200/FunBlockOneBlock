<?php

namespace cosmicnebula200\FunBlockOneBlock\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use cosmicnebula200\FunBlockOneBlock\player\Player;
use pocketmine\player\Player as P;
use pocketmine\command\CommandSender;

class DeleteSubCommand extends BaseSubCommand
{
    
    protected function prepare(): void
    {
        $this->setPermission('funblockoneblock.delete');
        $this->registerArgument(0, new RawStringArgument('name'));
    }
    
    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $name = $args['name'];
        if ($name !== $sender->getName() && !$sender->hasPermission('funblockoneblock.delete.others'))
        {
            $sender->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage('no-perms-delete'));
            return;
        }
        $oneBlockPlayer = FunBlockOneBlock::getInstance()->getPlayerManager()->getPlayerByPrefix($name);
        if (!$oneBlockPlayer instanceof Player)
        {
            $sender->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage('not-registered'));
            return;
        }
        $oneBlockPlayer->setOneBlock('');
        $player = FunBlockOneBlock::getInstance()->getServer()->getPlayerByPrefix($name);
        if ($player instanceof P)
            $player->teleport(FunBlockOneBlock::getInstance()->getServer()->getWorldManager()->getDefaultWorld()->getSpawnLocation());
        $sender->sendMessage();
    }

}
