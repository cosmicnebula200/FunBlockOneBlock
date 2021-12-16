<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\player;

use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;

class Player
{

    /**@var string*/
    private string $name, $oneBlock;

    public function __construct(string $name, string $oneBlock)
    {
        $this->name = $name;
        $this->oneBlock = $oneBlock;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOneBlock(): string
    {
        return $this->oneBlock;
    }

    public function setOneBlock(string $oneBlock): void
    {
        $this->oneBlock = $oneBlock;
        $this->save();
    }

    public function save(): void
    {
        FunBlockOneBlock::getInstance()->getDataBase()->executeChange('funblockoneblock.player.update', [
            'name' => $this->name,
            'oneblock' => $this->oneBlock
        ]);
        FunBlockOneBlock::getInstance()->getDataBase()->waitAll();
    }

}
