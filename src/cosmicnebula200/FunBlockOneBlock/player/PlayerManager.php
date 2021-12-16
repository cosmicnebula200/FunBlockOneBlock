<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\player;

use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use pocketmine\player\Player as P;

class PlayerManager
{

    /**@var Player[]*/
    private array $players = [];

    public function __construct()
    {
        FunBlockOneBlock::getInstance()->getDataBase()->executeSelect(
            'funblockoneblock.player.load',
            [],
            function (array $rows): void
            {
                foreach ($rows as $row)
                    $this->players[$row['name']] = new Player($row['name'], $row['oneblock']);
            }
        );
    }

    public function createPlayer(P $player): void
    {
        FunBlockOneBlock::getInstance()->getDataBase()->executeInsert('funblockoneblock.player.init',
        [
            'name' => $player->getName(),
            'oneblock' => ''
        ]);
        $this->players[$player->getName()] = new Player($player->getName(), '');
        FunBlockOneBlock::getInstance()->getDataBase()->waitAll();
    }

    public function getPlayer(P $player): Player
    {
        return $this->players[$player->getName()];
    }

    public function getPlayerByPrefix(string $name): ?Player
    {
        return $this->players[$name]?? null;
    }

}
