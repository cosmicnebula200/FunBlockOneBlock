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
        $this->levelsConfig = new Config(FunBlockOneBlock::getInstance()->getDataFolder() . "messages.yml", Config::YAML);
        foreach ($this->levelsConfig->get('levels', []) as $level => $data)
        {
            $blocks = $data['blocks'];
            $blockArray = [];
            $xpArray = [];
            foreach ($blocks as $block)
            {
                for ($i = 0; $i <= $block['chance']; $i++)
                {
                    $blockArray[] = "$block:{$block['meta']}";
                }
                $xpArray = $data['xp'];
            }
            shuffle($blockArray);
            $this->levels[$level] = new Level($data['name'], $data['levelup'], $blockArray, $xpArray);
        }
    }

    public function getLevel(int $level): ?Level
    {
        return $this->levels[$level] ?? null;
    }


}
