<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\commands\subcommands;

use CortexPE\Commando\BaseSubCommand;
use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\world\Position;

class GoSubCommand extends BaseSubCommand
{

    protected function prepare(): void
    {
        $this->setPermission('funblockoneblock.go');
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player)
            return;
        $oneBlock = FunBlockOneBlock::getInstance()->getPlayerManager()->getPlayer($sender)->getOneBlock();
        if ($oneBlock == '')
        {
            $sender->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage('no-ob-go'));
            return;
        }
        $spawn = FunBlockOneBlock::getInstance()->getOneBlockManager()->getOneBlockByUuid($oneBlock)->getSpawn();
        $sender->teleport(Position::fromObject($spawn->up(), FunBlockOneBlock::getInstance()->getServer()->getWorldManager()->getWorldByName(FunBlockOneBlock::getInstance()->getOneBlockManager()->getOneBlockByUuid($oneBlock)->getWorld())));
    }
}
