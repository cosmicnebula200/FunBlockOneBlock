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
    /** @var array */
    private array $worlds = [];

    public function loadOneBlock(string $uuid): void
    {
        FunBlockOneBlock::getInstance()->getDataBase()->executeSelect(
            'funblockoneblock.oneblock.load',
            [
                'uuid' => $uuid
            ],
            function (array $rows) use ($uuid): void
            {
                if (count($rows) == 0)
                    return;
                $row = $rows[0];
                if (isset($this->oneBlocks['name']))
                    return;
                $spawn = (array)json_decode($row['spawn']);
                $this->oneBlocks[$row['uuid']] = new OneBlock($row['uuid'], $row['name'], $row['leader'], explode(',', $row['members']), $row['world'], $row['xp'], $row['level'], (array)json_decode($row['settings']), new Vector3($spawn["x"], $spawn["y"], $spawn['z']));
                FunBlockOneBlock::getInstance()->getServer()->getWorldManager()->loadWorld($row['world']);
                $this->worlds[] = $row['world'];
            }
        );
    }

    public function unloadOneBlock(string $uuid)
    {
        foreach ($this->getOneBlockByUuid($uuid)->getMembers() as $member)
        {
            if (FunBlockOneBlock::getInstance()->getPlayerManager()->getPlayerByPrefix($member) instanceof Player)
                return;
        }
        FunBlockOneBlock::getInstance()->getServer()->getWorldManager()->unloadWorld(FunBlockOneBlock::getInstance()->getServer()->getWorldManager()->getWorldByName($this->getOneBlockByUuid($uuid)->getWorld()));
        unset($this->oneBlocks[$uuid]);
    }

    public function makeOneBlock(string $uuid, Player $player, string $name, World $world): void
    {
        $spawn = $world->getSpawnLocation();
        $oneBlock = new OneBlock($uuid, $name, $player->getName(), [$player->getName()], $world->getFolderName(), 0, 1, ['visit' => true, 'pvp' => false], $spawn);
        $this->oneBlocks[$name] = $oneBlock;
        $this->worlds[] = $world->getFolderName();
        FunBlockOneBlock::getInstance()->getDataBase()->executeInsert('funblockoneblock.oneblock.create', [
            'uuid' => $uuid,
            'name' => $name,
            'leader' => $player->getName(),
            'members' => implode(',', [$player->getName()]),
            'world' => $world->getFolderName(),
            'xp' => 0,
            'level' => 0,
            'settings' => json_encode(['visit' => true, 'pvp' => false]),
            'spawn' => json_encode([
                'x' => $spawn->getX(),
                'y' => $spawn->getY(),
                'z' => $spawn->getZ()
            ])
        ]);
        $oneBlock->save();
    }

    public function getOneBlockByUuid(string $uuid): ?OneBlock
    {
        return $this->oneBlocks[$uuid] ?? null;
    }

    public function getOneBlock(string $name): ?OneBlock
    {
        foreach ($this->oneBlocks as $oneBlock)
        {
            if ($oneBlock->getName() == $name)
                return $oneBlock;
        }
        return null;
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
        if (in_array($world, $this->worlds))
            return true;
        return false;
    }

    public function deleteOneBlock(string $uuid): void
    {
        unset($this->oneBlocks[$uuid]);
        FunBlockOneBlock::getInstance()->getDataBase()->executeGeneric(
            'funblockoneblock.oneblock.delete', [
                'uuid' => $uuid
            ]
        );
    }

}
