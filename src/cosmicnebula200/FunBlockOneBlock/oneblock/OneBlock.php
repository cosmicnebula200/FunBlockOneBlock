<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\oneblock;

use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use cosmicnebula200\FunBlockOneBlock\level\Level;
use pocketmine\math\Vector3;

class OneBlock
{

    /**@var string*/
    private string $name, $leader, $world;
    /**@var array*/
    private array $members;
    /**@var int*/
    private int $xp;
    /** @var Level */
    private Level $level;
    /**@var Vector3*/
    private Vector3 $spawn;

    public function __construct(string $name, string $leader, array $members, string $world, int $xp, int $level, Vector3 $spawn)
    {
        $this->name = $name;
        $this->leader = $leader;
        $this->members = $members;
        $this->world = $world;
        $this->xp = $xp;
        $this->level = FunBlockOneBlock::getInstance()->getLevelManager()->getLevel($level);
        $this->spawn = $spawn;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
        $this->save();
    }

    /**
     * @return string
     */
    public function getLeader(): string
    {
        return $this->leader;
    }

    /**
     * @param string $leader
     */
    public function setLeader(string $leader): void
    {
        $this->leader = $leader;
        $this->save();
    }

    /**
     * @return array
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * @param array $members
     */
    public function setMembers(array $members): void
    {
        $this->members = $members;
        $this->save();
    }

    /**
     * @return string
     */
    public function getWorld(): string
    {
        return $this->world;
    }

    /**
     * @param string $world
     */
    public function setWorld(string $world): void
    {
        $this->world = $world;
        $this->save();
    }

    /**
     * @return int
     */
    public function getXp(): int
    {
        return $this->xp;
    }

    /**
     * @param int $xp
     */
    public function setXp(int $xp): void
    {
        $this->xp = $xp;
        $this->save();
    }

    /**
     * @return Level
     */
    public function getLevel(): Level
    {
        return $this->level;
    }

    /**
     * @param Level $level
     */
    public function setLevel(Level $level): void
    {
        $this->level = $level;
        $this->save();
    }

    /**
     * @return Vector3
     */
    public function getSpawn(): Vector3
    {
        return $this->spawn;
    }

    /**
     * @param Vector3 $spawn
     */
    public function setSpawn(Vector3 $spawn): void
    {
        $this->spawn = $spawn;
        $this->save();
    }

    public function save(): void
    {
        FunBlockOneBlock::getInstance()->getDataBase()->executeChange('oneblock.islands.update', [
            'name' => $this->name,
            'leader' => $this->leader,
            'members' => implode(',', $this->members),
            'world' => $this->world,
            'xp' => $this->xp,
            'level' => $this->level,
            'spawn' => json_encode([
                'x' => $this->spawn->getX(),
                'y' => $this->spawn->getY(),
                'z' => $this->spawn->getZ()
            ])
        ]);
    }

}
