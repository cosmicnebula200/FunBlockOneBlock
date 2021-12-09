<?php

namespace cosmicnebula200\FunBlockOneBlock\listener;

use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Food;

class EventListener implements Listener
{

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
        if ($block->getPosition()->getWorld()->getBlock($block->getPosition()->subtract(0,1,0)) === VanillaBlocks::BARRIER())
        {
            $block->getPosition()->getWorld()->setBlock($block->getPosition(), $world->getLevel()->getRandomBlock());
            if ($world->getLevel()->getBlockXp($event->getBlock()) !== null)
                $world->setXp($world->getXp() + $world->getLevel()->getBlockXp($event->getBlock()));
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
