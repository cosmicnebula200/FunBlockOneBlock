<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\player;

use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;

class PlayerManager
{

    /**@var Player[]*/
    private array $players;

    public function __construct()
    {
        FunBlockOneBlock::getInstance()->getDataBase()->executeSelect(
            'oneblock.player.load',
            [],
            function (array $rows): void
            {
                foreach ($rows as $row)
                    $this->players[$row['name']] = new Player($row['name'], $row['oneblock']);
            }
        );
    }

    public function createPlayer(Player $player): void
    {
        FunBlockOneBlock::getInstance()->getDataBase()->executeInsert('funblockoneblock.player.init',
        [
            'name' => $player->getName(),
            'oneblock' => ''
        ]);
    }

    public function getPlayer(\pocketmine\player\Player $player): Player
    {
        return $this->players[$player->getName()];
    }

    public function getPlayerByPrefix(string $name): ?Player
    {
        return $this->players[$name];
    }

}
