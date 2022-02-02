<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\commands\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\player\PlayerChunkLoader;
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
        $chunkX = $world->getSpawnLocation()->getX() >> 4;
        $chunkZ = $world->getSpawnLocation()->getZ() >> 4;
        $world->requestChunkPopulation($chunkX, $chunkZ, new PlayerChunkLoader($world->getSpawnLocation()))->onCompletion(
            function () use ($world, $sender, $id, $player, $args){
                $world->setBlock($world->getSpawnLocation(), VanillaBlocks::GRASS());
                $world->setBlock($world->getSpawnLocation()->subtract(0, 1,0), VanillaBlocks::BARRIER());
                $sender->teleport($world->getSpawnLocation());
                $sender->teleport($world->getSpawnLocation()->add(0, 1, 0));
                FunBlockOneBlock::getInstance()->getOneBlockManager()->makeOneBlock($id, $player, $args['name'], $world);
            }, function () {
                // NOthing
            }
        );
    }

}
