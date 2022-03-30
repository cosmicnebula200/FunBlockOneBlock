<?php

namespace cosmicnebula200\FunBlockOneBlock\listener;

use pocketmine\player\Player;

class  LevelChangeEvent extends OneBlockEvent
{

    public function __construct(public Player $player, public int $level, public string $levelName)
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
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @return string
     */
    public function getLevelName(): string
    {
        return $this->levelName;
    }

}
