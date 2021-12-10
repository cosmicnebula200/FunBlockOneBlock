<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\invites;

use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use pocketmine\player\Player;

class Invite
{

    /** @var Player|null */
    private ?Player $inviter, $receiver;

    public function __construct(Player $inviter, Player $receiver)
    {
        $this->inviter = $inviter;
        $this->receiver = $receiver;
    }

    public function getInviter(): ?Player
    {
        return $this->inviter;
    }

    public function getReceiver(): ?Player
    {
        return $this->receiver;
    }

    public function cancel(): void
    {
        $players = [$this->inviter, $this->receiver];
        foreach ($players as $player)
        {
            if ($player instanceof Player)
                $player->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage('invite-expired'));
        }
    }

    public function handleInvite(): bool
    {
        if (!$this->inviter instanceof Player)
            return false;
        $inviter = FunBlockOneBlock::getInstance()->getPlayerManager()->getPlayerByPrefix($this->inviter->getName());
        $receiver = FunBlockOneBlock::getInstance()->getPlayerManager()->getPlayerByPrefix($this->receiver->getName());
        if ($inviter->getOneBlock() == '' || $receiver->getOneBlock() !== '')
            return false;
        return true;
    }

}
