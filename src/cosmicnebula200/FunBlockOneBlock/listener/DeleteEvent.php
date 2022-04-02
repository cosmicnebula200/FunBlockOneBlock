<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\listener;

use cosmicnebula200\FunBlockOneBlock\listener\OneBlockEvent;
use cosmicnebula200\FunBlockOneBlock\oneblock\OneBlock;

class DeleteEvent extends OneBlockEvent
{

    public function __construct(public OneBlock $oneBlock)
    {

    }

    /**
     * @return OneBlock
     */
    public function getOneBlock(): OneBlock
    {
        return $this->oneBlock;
    }

}
