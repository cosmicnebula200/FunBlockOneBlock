<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\generator;

use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use pocketmine\world\generator\Flat;
use pocketmine\world\WorldCreationOptions;

class Generator
{

    public function generateWorld(string $name): void
    {
        $wco = new WorldCreationOptions();
        $wco->setGeneratorClass(Flat::class);
        $wco->setGeneratorOptions("2;64x0");
        FunBlockOneBlock::getInstance()->getServer()->getWorldManager()->generateWorld($name, $wco);
    }

}