<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\listener;

use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use cosmicnebula200\FunBlockOneBlock\level\Level;
use cosmicnebula200\FunBlockOneBlock\oneblock\OneBlock;
use cosmicnebula200\FunBlockOneBlock\player\Player;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Food;
use pocketmine\player\Player as P;
use pocketmine\scheduler\ClosureTask;

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
        $oneblock = FunBlockOneBlock::getInstance()->getOneBlockManager()->getOneBlockByWorld($block->getPosition()->getWorld());
        if (!in_array($event->getPlayer()->getName(), $oneblock->getMembers()))
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
                    $drops[] = $drop;
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
        if ($block->getPosition()->getWorld()->getBlock($block->getPosition()->subtract(0,1,0))->getId() == VanillaBlocks::BARRIER()->getId())
        {
            if ($oneblock->getLevel()->getBlockXp($event->getBlock()) !== null)
            {
                $newXP = $oneblock->getXp() + $oneblock->getLevel()->getBlockXp($event->getBlock());
                $newLevel = FunBlockOneBlock::getInstance()->getLevelManager()->getLevel($oneblock->getLevel()->asInt() + 1);
                if ($newXP >= $oneblock->getLevel()->getLevelUpXp() && $newLevel instanceof Level)
                {
                    $oneblock->setLevel($newLevel);
                    if (FunBlockOneBlock::getInstance()->getConfig()->getNested('settings.reset-xp'))
                    {
                        $oneblock->setXp($newXP - $oneblock->getLevel()->getLevelUpXp());
                    }
                }
            }
            FunBlockOneBlock::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use($block, $oneblock): void {
                $block->getPosition()->getWorld()->setBlock($block->getPosition(), $oneblock->getLevel()->getRandomBlock());
            }), 1);
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
        $oneblock = FunBlockOneBlock::getInstance()->getOneBlockManager()->getOneBlockByWorld($event->getBlock()->getPosition()->getWorld());
        if (!in_array($event->getPlayer()->getName(), $oneblock->getMembers()))
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
        $oneblock = FunBlockOneBlock::getInstance()->getOneBlockManager()->getOneBlockByWorld($event->getPlayer()->getWorld());
        if (!in_array($event->getPlayer()->getName(), $oneblock->getMembers()))
        {
            $event->cancel();
        }
    }

    /**
     * @param EntityDamageEvent $event
     * @return void
     */
    public function onPlayerDamage(EntityDamageEvent $event): void
    {
        $entity = $event->getEntity();
        if (!$entity instanceof P)
            return;
        if (!FunBlockOneBlock::getInstance()->getOneBlockManager()->getOneBlockByWorld($entity->getWorld()) instanceof OneBlock)
            return;
        $type = match ($event->getCause()) {
            EntityDamageEvent::CAUSE_ENTITY_ATTACK => 'player',
            EntityDamageEvent::CAUSE_LAVA => 'lava',
            EntityDamageEvent::CAUSE_DROWNING => 'drown',
            EntityDamageEvent::CAUSE_FALL => 'fall',
            EntityDamageEvent::CAUSE_PROJECTILE => 'projectile',
            EntityDamageEvent::CAUSE_FIRE => 'fire',
            EntityDamageEvent::CAUSE_VOID => 'void',
            EntityDamageEvent::CAUSE_STARVATION => 'hunger',
            default => 'default'
        };
        if (FunBlockOneBlock::getInstance()->getConfig()->getNested("settings.damage.$type", true))
            $event->cancel();
    }

}
