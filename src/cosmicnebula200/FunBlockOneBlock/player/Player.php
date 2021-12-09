<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\player;

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
    }


}
