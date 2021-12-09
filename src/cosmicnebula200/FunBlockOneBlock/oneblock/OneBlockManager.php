<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\oneblock;

use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use cosmicnebula200\FunBlockOneBlock\player\Player;
use pocketmine\math\Vector3;
use pocketmine\world\World;

class OneBlockManager
{

    /**@var OneBlock[]*/
    private array $oneBlocks = [];

    public function __construct()
    {
        FunBlockOneBlock::getInstance()->getDataBase()->executeSelect(
            'funblockoneblock.oneblock.load',
            [],
            function (array $rows): void
            {
                foreach ($rows as $row)
                {
                    $spawn = (array)json_decode($row['spawn']);
                    $this->oneBlocks[$row['name']] = new OneBlock($row['name'], $row['leader'], explode(',', $row['members']), $row['world'], $row['xp'], $row['level'], new Vector3($spawn["x"], $spawn["y"], $spawn['z']));
                }
            }
        );
    }

    public function makeOneBlock(Player $player, string $name, World $world): void
    {
        $oneBlock = new OneBlock($name, $player->getName(), [$player->getName()], $world->getFolderName(), 0, 1, $world->getSpawnLocation());
        $this->oneBlocks[$name] = $oneBlock;
        $oneBlock->save();
    }

    public function getOneBlock(string $name): ?OneBlock
    {
        return $this->oneBlocks[$name];
    }

    public function getOneBlockByWorld(World $world): ?OneBlock
    {
        foreach ($this->oneBlocks as $oneBlock)
        {
            if ($oneBlock->getWorld() == $world->getDisplayName())
                return $oneBlock;
        }
        return null;
    }

    public function isOneBlockWorld(string $world): bool
    {
        foreach ($this->oneBlocks as $oneBlock)
        {
            if ($oneBlock->getWorld() == $world)
                return true;
        }
        return false;
    }

}
