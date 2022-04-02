<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\commands\subcommands;

use CortexPE\Commando\BaseSubCommand;
use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use cosmicnebula200\FunBlockOneBlock\oneblock\OneBlock;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class LeaveSubCommand extends BaseSubCommand
{

    protected function prepare(): void
    {
        $this->setPermission('funblockoneblock.leave');
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player)
            return;
        $oneBlock = FunBlockOneBlock::getInstance()->getOneBlockManager()->getOneBlockByUuid(FunBlockOneBlock::getInstance()->getPlayerManager()->getPlayer($sender)->getUuid());
        if (!$oneBlock instanceof OneBlock)
        {
            $sender->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage('no-ob'));
            return;
        }
        if ($sender->getName() == $oneBlock->getLeader())
        {
            $sender->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage("leader-no-leave"));
            return;
        }
        $members = $oneBlock->getMembers();
        unset($members[array_search($sender->getName(), $members)]);
        $oneBlock->setMembers($members);
        $sender->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage('left-ob'));
    }

}
