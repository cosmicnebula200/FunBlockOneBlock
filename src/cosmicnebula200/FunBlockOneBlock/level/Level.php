<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\level;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;

class Level
{

    /** @var int */
    private int $level, $levelUpXp;
    /** @var string */
    private string $name;
    /** @var array */
    private array $blocks, $xp;

    public function __construct(int $level, string $name, int $levelUpXp, array $blocks, array $xp)
    {
        $this->level = $level;
        $this->name = $name;
        $this->levelUpXp = $levelUpXp;
        $this->blocks = $blocks;
        $this->xp = $xp;
    }

    public function asInt(): int
    {
        return $this->level;
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
        $block = explode(':' ,$this->blocks[mt_rand(0, count($this->blocks) -1)]);
        return BlockFactory::getInstance()->get((int)$block[0], (int)$block[1]);
    }

}
