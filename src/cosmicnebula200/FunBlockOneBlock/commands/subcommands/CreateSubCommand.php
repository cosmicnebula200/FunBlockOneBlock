<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
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
        $sender->sendMessage(FunBlockOneBlock::getInstance()->getMessages()->getMessage('world-generating'));
        $id = Uuid::uuid4()->toString();
        $player->setOneBlock($id);
        FunBlockOneBlock::getInstance()->getGenerator()->generateWorld($id);
        $world = FunBlockOneBlock::getInstance()->getServer()->getWorldManager()->getWorldByName($id);
        FunBlockOneBlock::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($id, $sender, $world, $player, $args): void {
            $sender->teleport($world->getSpawnLocation());
            $sender->teleport($sender->getWorld()->getSpawnLocation()->add(0, 1, 0));
            $sender->setImmobile(true);
            $sender->getWorld()->setBlock($world->getSpawnLocation(), VanillaBlocks::DIRT());
            $sender->getWorld()->setBlock($world->getSpawnLocation()->subtract(0,1,0), VanillaBlocks::BARRIER());
            $sender->setImmobile(false);
            FunBlockOneBlock::getInstance()->getOneBlockManager()->makeOneBlock($id, $player, $args['name'], $world);
        }), 100);
    }

}
