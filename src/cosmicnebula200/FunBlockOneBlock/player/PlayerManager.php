<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\player;

use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use pocketmine\player\Player as P;

class PlayerManager
{

    /**@var Player[]*/
    private array $players = [];

    public function loadPlayer(P $player)
    {
        FunBlockOneBlock::getInstance()->getDataBase()->executeSelect(
            'funblockoneblock.player.load',
            [
                'uuid' => $player->getUniqueId()->toString()
            ],
            function (array $rows) use ($player): void
            {
                if (count($rows) == 0) {
                    $this->createPlayer($player);
                    return;
                }
                $this->players[$rows[0]['name']] = new Player($rows[0]['uuid'], $rows[0]['name'], $rows[0]['oneblock']);
                FunBlockOneBlock::getInstance()->getOneBlockManager()->loadOneBlock($rows[0]['oneblock']);
            }
        );
    }

    public function unloadPlayer(P $player)
    {
        $player = $this->getPlayer($player);
        if (!$player instanceof Player)
            return;
        $oneBlock = $player->getOneBlock();
        if ($oneBlock !== '')
            FunBlockOneBlock::getInstance()->getOneBlockManager()->unloadOneBlock($oneBlock);
        unset($this->players[$player->getName()]);
    }

    public function createPlayer(P $player): void
    {
        FunBlockOneBlock::getInstance()->getDataBase()->executeInsert('funblockoneblock.player.create',
        [
            'uuid' => $player->getUniqueId()->toString(),
            'name' => $player->getName(),
            'oneblock' => ''
        ]);
        $this->players[$player->getName()] = new Player($player->getUniqueId()->toString(), $player->getName(), '');
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
