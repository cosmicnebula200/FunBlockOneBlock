<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use cosmicnebula200\FunBlockOneBlock\libs\SimpleForm;
use cosmicnebula200\FunBlockOneBlock\oneblock\OneBlock;
use cosmicnebula200\FunBlockOneBlock\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\player\Player as P;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class VisitSubCommand extends BaseSubCommand
{

    protected function prepare(): void
    {
        $this->setPermission('funblockoneblock.visit');
        $this->registerArgument(0, new RawStringArgument('name', true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!($sender instanceof P))
            return;
        if (isset($args['name']))
        {
            $p = FunBlockOneBlock::getInstance()->getPlayerManager()->getPlayerByPrefix($args['name']);
            if (!$p instanceof Player)
            {
                $sender->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage('not-registered'));
                return;
            }
            $oneblock = FunBlockOneBlock::getInstance()->getOneBlockManager()->getOneBlock($p->getOneBlock());
            if (!$oneblock instanceof OneBlock)
            {
                $sender->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage('no-island'));
                return;
            }
            $sender->teleport(FunBlockOneBlock::getInstance()->getServer()->getWorldManager()->getWorldByName($oneblock->getWorld())->getSpawnLocation());
            $sender->teleport($oneblock->getSpawn());
        }
        $oneblocks = [];
        foreach (FunBlockOneBlock::getInstance()->getServer()->getOnlinePlayers() as $player)
        {
            $oneblock = FunBlockOneBlock::getInstance()->getOneBlockManager()->getOneBlock(FunBlockOneBlock::getInstance()->getPlayerManager()->getPlayer($player)->getOneBlock());
            if  ($oneblock instanceof OneBlock)
                $oneblocks[] = $oneblock;
        }
        $form = new SimpleForm(function (P $player, ?int $data) use ($oneblocks) {
            if (!isset($oneblocks[$data]))
                return;
            $oneblock = $oneblocks[$data];
            $player->teleport(FunBlockOneBlock::getInstance()->getServer()->getWorldManager()->getWorldByName($oneblock->getWorld())->getSpawnLocation());
            $player->teleport($oneblock->getSpawn());
        });
        $formConfig = new Config(FunBlockOneBlock::getInstance()->getDataFolder() . "/forms.yml", Config::YAML);
        $form->setTitle(TextFormat::colorize($formConfig->getNested('visit.title')));
        foreach ($oneblocks as $oneblock)
        {
            $form->addButton(TextFormat::colorize(str_replace('{NAME}', $oneblock->getLeader() , $formConfig->getNested('visit.buttons', '&l&a{NAME} OneBlock'))));
        }
        $form->sendToPlayer($sender);
    }

}
