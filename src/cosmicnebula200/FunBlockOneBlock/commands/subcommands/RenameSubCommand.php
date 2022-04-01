<?php

namespace cosmicnebula200\FunBlockOneBlock\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use cosmicnebula200\FunBlockOneBlock\oneblock\OneBlock;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class RenameSubCommand extends BaseSubCommand
{

    protected function prepare(): void
    {
        $this->setPermission('funblockoneblock.rename');
        $this->registerArgument(0, new RawStringArgument('name'));
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
        if ($sender->getName() !== $oneBlock->getLeader())
        {
            $sender->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage("not-leader"));
            return;
        }
        $oneBlock->setName($args['name']);
        $sender->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage("renamed", [
            "name" => $args['name']
        ]));
    }

}
