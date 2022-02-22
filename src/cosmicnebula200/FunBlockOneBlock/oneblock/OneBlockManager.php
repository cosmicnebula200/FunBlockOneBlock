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
    /** @var string[] */
    private array $names = [];

    public function loadOneBlock(string $uuid): void
    {
        FunBlockOneBlock::getInstance()->getDataBase()->executeSelect(
            'funblockoneblock.oneblock.load',
            [
                'uuid' => $uuid
            ],
            function (array $rows): void
            {
                if (count($rows) == 0)
                    return;
                $row = $rows[0];
                if (isset($this->oneBlocks[$row['uuid']]))
                    return;
                $spawn = (array)json_decode($row['spawn']);
                $this->oneBlocks[$row['uuid']] = new OneBlock($row['uuid'], $row['name'], $row['leader'], explode(',', $row['members']), $row['world'], $row['xp'], $row['level'], (array)json_decode($row['settings']), new Vector3($spawn["x"], $spawn["y"], $spawn['z']));
                FunBlockOneBlock::getInstance()->getServer()->getWorldManager()->loadWorld($row['world']);
                $this->worlds[$row['world']] = $row['uuid'];
                $this->names[$row['uuid']] = $row['uuid'];
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
        $oneBlock = $this->getOneBlockByUuid($uuid);
        unset($this->worlds[$oneBlock->getWorld()]);
        unset($this->names[$oneBlock->getName()]);
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
        if (isset($this->names[$name]))
            return $this->getOneBlockByUuid($this->names[$name]) ?? null;
        return null;
    }

    public function getOneBlockByWorld(World $world): ?OneBlock
    {
        if (isset($this->worlds[$world->getFolderName()]))
            return $this->oneBlocks[$world->getFolderName()] ?? null;
        return null;
    }

    public function isOneBlockWorld(string $world): bool
    {
        if (isset($this->worlds[$world]))
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
