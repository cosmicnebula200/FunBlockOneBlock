<?php

namespace cosmicnebula200\FunBlockOneBlock\listener;

use cosmicnebula200\FunBlockOneBlock\oneblock\OneBlock;
use pocketmine\player\Player;

class CreationEvent extends OneBlockEvent
{

    public function __construct(public Player$player, public OneBlock $oneBlock)
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
     * @return OneBlock
     */
    public function getOneBlock(): OneBlock
    {
        return $this->oneBlock;
    }

}
