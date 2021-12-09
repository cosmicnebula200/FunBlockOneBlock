<?php

namespace cosmicnebula200\FunBlockOneBlock\level;

use pocketmine\block\Block;

class Level
{

    /** @var string */
    private $name;
    /** @var int */
    private int $levelUpXp;
    /** @var array */
    private array $blocks, $xp;

    public function __construct(string $name, int $levelUpXp, array $blocks, array $xp)
    {
        $this->name = $name;
        $this->levelUpXp = $levelUpXp;
        $this->blocks = $blocks;
        $this->xp = $xp;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLevelUpXp(): int
    {
        return $this->levelUpXp;
    }

    public function getBlockXp($block): ?int
    {
        if ($block instanceof Block)
            $block = $block->getId();
        return $this->xp[$block] ?? null;
    }

    public function getRandomBlock(): Block
    {
        return $this->blocks[mt_rand(0, count($this->blocks) -1)];
    }

}
