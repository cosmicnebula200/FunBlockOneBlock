<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\commands;

use CortexPE\Commando\BaseCommand;
use cosmicnebula200\FunBlockOneBlock\commands\subcommands\CreateSubCommand;
use cosmicnebula200\FunBlockOneBlock\commands\subcommands\DeleteSubCommand;
use cosmicnebula200\FunBlockOneBlock\commands\subcommands\GoSubCommand;
use pocketmine\command\CommandSender;

class OneBlockCommand extends BaseCommand
{

    public function prepare(): void
    {
        $this->setPermission('funblockoneblock.command');
        $this->registerSubCommand(new CreateSubCommand('create', 'Creates a OneBlock Island incase the sender does not have one',['c', 'make']));
        $this->registerSubCommand(new DeleteSubCommand('delete', 'Deletes the mentioned users OneBlock Island'));
        $this->registerSubCommand(new GoSubCommand('go', 'Teleports to the users OneBlock island'));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        // nothing
    }

}
