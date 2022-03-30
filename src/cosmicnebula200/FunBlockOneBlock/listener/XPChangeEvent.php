<?php

namespace cosmicnebula200\FunBlockOneBlock\listener;

use pocketmine\player\Player;

class XPChangeEvent extends OneBlockEvent
{

    public function __construct(public Player $player, public int $xp)
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
     * @return int
     */
    public function getXp(): int
    {
        return $this->xp;
    }

}
