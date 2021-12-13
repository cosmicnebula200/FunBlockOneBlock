<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\listener;

use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use cosmicnebula200\FunBlockOneBlock\level\Level;
use cosmicnebula200\FunBlockOneBlock\player\Player;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Food;

class EventListener implements Listener
{

    /**
     * @param PlayerJoinEvent $event
     * @return void
     */
    public function onJoin(PlayerJoinEvent $event): void
    {
        $player = FunBlockOneBlock::getInstance()->getPlayerManager()->getPlayerByPrefix($event->getPlayer()->getName());
        if (!$player instanceof Player)
            FunBlockOneBlock::getInstance()->getPlayerManager()->createPlayer($event->getPlayer());
    }

    /**
     * @param BlockBreakEvent $event
     * @return void
     */
    public function onBreak(BlockBreakEvent $event): void
    {
        if (!FunBlockOneBlock::getInstance()->getOneBlockManager()->isOneBlockWorld($event->getPlayer()->getWorld()->getFolderName()))
            return;
        $block = $event->getBlock();
        $world = FunBlockOneBlock::getInstance()->getOneBlockManager()->getOneBlockByWorld($block->getPosition()->getWorld());
        if (!in_array($event->getPlayer()->getName(), $world->getMembers()))
        {
            $event->cancel();
            return;
        }
        if (FunBlockOneBlock::getInstance()->getConfig()->getNested('settings.autoinv.enabled', true))
        {
            $drops = [];
            foreach ($event->getDrops() as $drop)
            {
                if (!$event->getPlayer()->getInventory()->canAddItem($drop))
                    $drop[] = $drop;
                else
                    $event->getPlayer()->getInventory()->addItem($drop);
            }
            $event->setDrops([]);
            if (FunBlockOneBlock::getInstance()->getConfig()->getNested('settings.autoinv.drop-when-full'))
                $event->setDrops($drops);
        }
        if (FunBlockOneBlock::getInstance()->getConfig()->getNested('settings.autoxp', true))
        {
            $event->getPlayer()->getXpManager()->addXp($event->getXpDropAmount());
            $event->setXpDropAmount(0);
        }
        if ($block->getPosition()->getWorld()->getBlock($block->getPosition()->subtract(0,1,0)) === VanillaBlocks::BARRIER())
        {
            $block->getPosition()->getWorld()->setBlock($block->getPosition(), $world->getLevel()->getRandomBlock());
            if ($world->getLevel()->getBlockXp($event->getBlock()) !== null)
            {
                $newXP = $world->getXp() + $world->getLevel()->getBlockXp($event->getBlock());
                $newLevel = FunBlockOneBlock::getInstance()->getLevelManager()->getLevel($world->getLevel()->asInt() + 1);
                if ($newXP >= $world->getLevel()->getLevelUpXp() && $newLevel instanceof Level)
                {
                    $world->setLevel($newLevel);
                    if (FunBlockOneBlock::getInstance()->getConfig()->getNested('settings.reset-xp'))
                    {
                        $world->setXp($newXP - $world->getLevel()->getLevelUpXp());
                    }
                }
            }
        }
    }

    /**
     * @param BlockPlaceEvent $event
     * @return void
     */
    public function onPlace(BlockPlaceEvent $event): void
    {
        if (!FunBlockOneBlock::getInstance()->getOneBlockManager()->isOneBlockWorld($event->getPlayer()->getWorld()->getFolderName()))
            return;
        $world = FunBlockOneBlock::getInstance()->getOneBlockManager()->getOneBlockByWorld($event->getBlock()->getPosition()->getWorld());
        if (!in_array($event->getPlayer()->getName(), $world->getMembers()))
        {
            $event->cancel();
        }
    }

    /**
     * @param PlayerInteractEvent $event
     * @return void
     */
    public function onInteract(PlayerInteractEvent $event): void
    {
        if (!FunBlockOneBlock::getInstance()->getOneBlockManager()->isOneBlockWorld($event->getPlayer()->getWorld()->getFolderName()))
            return;
        if ($event->getItem() instanceof Food)
            return;
        $world = FunBlockOneBlock::getInstance()->getOneBlockManager()->getOneBlockByWorld($event->getPlayer()->getWorld());
        if (!in_array($event->getPlayer()->getName(), $world->getMembers()))
        {
            $event->cancel();
        }
    }

}
