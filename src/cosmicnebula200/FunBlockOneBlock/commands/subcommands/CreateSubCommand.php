<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use Ramsey\Uuid\Uuid;

class CreateSubCommand extends BaseSubCommand
{

    protected function prepare(): void
    {
        $this->setPermission('funblockoneblock.create');
        $this->registerArgument(0, new RawStringArgument('name'));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player)
            return;
        $player = FunBlockOneBlock::getInstance()->getPlayerManager()->getPlayer($sender);
        if ($player->getOneBlock() !== '')
        {
            $sender->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage('have-ob'));
            return;
        }
        $id = Uuid::uuid4()->toString();
        $player->setOneBlock($id);
        FunBlockOneBlock::getInstance()->getGenerator()->generateWorld($id);
        $spawn = FunBlockOneBlock::getInstance()->getServer()->getWorldManager()->getWorldByName($id)->getSpawnLocation();
        $sender->teleport($spawn->add(0, 1, 0));
        $sender->setImmobile(true);
        $sender->getWorld()->setBlock($spawn, VanillaBlocks::DIRT());
        $sender->getWorld()->setBlock($spawn->subtract(0,1,0), VanillaBlocks::BARRIER());
        $sender->setImmobile(false);
    }

}
