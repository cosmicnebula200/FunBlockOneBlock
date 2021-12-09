<?php

namespace cosmicnebula200\FunBlockOneBlock\level;

use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use pocketmine\utils\Config;

class LevelManager
{

    /** @var Config */
    private Config $levelsConfig;
    /** @var Level[] */
    private array $levels;


    public function __construct()
    {
        $this->levelsConfig = new Config(FunBlockOneBlock::getInstance()->getDataFolder() . "levels.yml", Config::YAML);
        foreach ($this->levelsConfig->get('levels', []) as $level => $data)
        {
            $blocks = $data['blocks'];
            $blockArray = [];
            $xpArray = [];
            foreach ($blocks as $block => $value)
            {
                for ($i = 0; $i <= $value['chance']; $i++)
                {
                    $blockArray[] = "$block:{$value['meta']}";
                }
                $xpArray[] = $value['xp'];
            }
            shuffle($blockArray);
            $this->levels[$level] = new Level($level, $data['name'], $data['levelup'], $blockArray, $xpArray);
        }
    }

    public function getLevel(int $level): ?Level
    {
        return $this->levels[$level] ?? null;
    }

}
