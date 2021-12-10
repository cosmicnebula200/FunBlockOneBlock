<?php

namespace cosmicnebula200\FunBlockOneBlock\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use pocketmine\command\CommandSender;

class AcceptSubCommand extends BaseSubCommand
{

    protected function prepare(): void
    {
        $this->setPermission('funblockoneblock.accept');
        $this->registerArgument(0, new RawStringArgument('name'));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $invite = FunBlockOneBlock::getInstance()->getInviteManager()->getPlayerInvites($args['name']);
    }

}
