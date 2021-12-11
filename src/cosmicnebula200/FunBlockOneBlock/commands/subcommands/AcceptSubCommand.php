<?php

namespace cosmicnebula200\FunBlockOneBlock\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use cosmicnebula200\FunBlockOneBlock\invites\Invite;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

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
        if (!$invite instanceof Invite)
            return;

        if (!$invite->handleInvite())
            return;

        $player = FunBlockOneBlock::getInstance()->getPlayerManager()->getPlayerByPrefix($sender->getName());
        $inviter = FunBlockOneBlock::getInstance()->getPlayerManager()->getPlayer($invite->getInviter());
        $player->setOneBlock($inviter->getOneBlock());
        $oneBlock = FunBlockOneBlock::getInstance()->getOneBlockManager()->getOneBlock($player->getOneBlock());
        foreach ($oneBlock->getMembers() as $member)
        {
            $mbr = FunBlockOneBlock::getInstance()->getServer()->getPlayerByPrefix($member);
            if ($mbr instanceof Player)
                $mbr->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage('invite-accepted', [
                    "{PLAYER}" => $sender->getName()
                ]));
        }
    }

}
