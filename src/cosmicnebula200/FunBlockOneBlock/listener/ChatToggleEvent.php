<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\listener;

use pocketmine\player\Player;

class ChatToggleEvent extends OneBlockEvent
{

    public function __construct(public Player $player, public string $status)
    {

    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return bool
     */
    public function getStatus(): string
    {
        return $this->status;
    }

}
