<?php

declare(strict_types=1);

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
        if ($name !== $sender->getName() && !$sender->hasPermission('funblockoneblock.deleteothers'))
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
        if ($oneBlockPlayer->getOneBlock() == '')
        {
            $sender->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage('no-island'));
            return;
        }
        $oneBlock = FunBlockOneBlock::getInstance()->getOneBlockManager()->getOneBlock($oneBlockPlayer->getOneBlock());
        foreach ($oneBlock->getMembers() as $member)
        {
            $player = FunBlockOneBlock::getInstance()->getServer()->getPlayerByPrefix($member);
            if ($player instanceof P)
                $player->teleport(FunBlockOneBlock::getInstance()->getServer()->getWorldManager()->getDefaultWorld()->getSpawnLocation());
            FunBlockOneBlock::getInstance()->getPlayerManager()->getPlayerByPrefix($member)->setOneBlock('');
        }
        $world = FunBlockOneBlock::getInstance()->getServer()->getWorldManager()->getWorldByName($oneBlock->getWorld());
        foreach ($world->getPlayers() as $p)
            $p->teleport(FunBlockOneBlock::getInstance()->getServer()->getWorldManager()->getDefaultWorld()->getSpawnLocation());
        if ($world->isLoaded())
            FunBlockOneBlock::getInstance()->getServer()->getWorldManager()->unloadWorld($world);
        // todo delete world folders ok
        $sender->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage('deleted-ob', [
            "{NAME}" => $oneBlockPlayer->getName()
        ]));
    }

}
