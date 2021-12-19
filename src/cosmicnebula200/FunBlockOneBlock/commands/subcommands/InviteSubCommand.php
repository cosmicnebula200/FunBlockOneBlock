<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use cosmicnebula200\FunBlockOneBlock\oneblock\OneBlock;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use Ramsey\Uuid\Uuid;

class InviteSubCommand extends BaseSubCommand
{

    protected function prepare(): void
    {
        $this->setPermission('funblockoneblock.invite');
        $this->registerArgument(0, new RawStringArgument('name'));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player)
            return;
        if (!FunBlockOneBlock::getInstance()->getInviteManager()->canInvite($sender))
        {
            $sender->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage('invite-pending'));
            return;
        }
        $player = FunBlockOneBlock::getInstance()->getServer()->getPlayerByPrefix($args['name']);
        $oneBlockPlayer = FunBlockOneBlock::getInstance()->getPlayerManager()->getPlayerByPrefix($sender->getName());
        $oneBlock = FunBlockOneBlock::getInstance()->getOneBlockManager()->getOneBlock($oneBlockPlayer->getOneBlock());
        if (!$oneBlock instanceof OneBlock)
        {
            $sender->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage('no-ob'));
            return;
        }
        if (count($oneBlock->getMembers()) >= FunBlockOneBlock::getInstance()->getConfig()->getNested('settings.max-members'))
        {
            $sender->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage('member-limit'));
            return;
        }
        if (!$player instanceof Player)
        {
            FunBlockOneBlock::getInstance()->getMessages()->getMessage('player-not-online');
            return;
        }
        if ($sender === $player)
            return;
        $id =  Uuid::uuid4()->toString();
        FunBlockOneBlock::getInstance()->getInviteManager()->addInvite($id, $sender, $player);
        $player->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage('invite-get', [
            "{INVITER}" => $sender->getName()
        ]));
        $sender->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage('invite-sent', [
            "{PLAYER}" => $player->getName()
        ]));
        FunBlockOneBlock::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($id): void {
            FunBlockOneBlock::getInstance()->getInviteManager()->cancelInvite($id);
        }), FunBlockOneBlock::getInstance()->getConfig()->getNested('settings.invite-timeout', 30) * 20);
    }

}
