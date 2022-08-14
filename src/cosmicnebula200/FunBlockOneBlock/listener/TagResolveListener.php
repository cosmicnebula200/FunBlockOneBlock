<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock\listener;

use cosmicnebula200\FunBlockOneBlock\FunBlockOneBlock;
use cosmicnebula200\FunBlockOneBlock\oneblock\OneBlock;
use Ifera\ScoreHud\event\PlayerTagsUpdateEvent;
use Ifera\ScoreHud\event\PlayerTagUpdateEvent;
use Ifera\ScoreHud\event\TagsResolveEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;

class TagResolveListener implements Listener
{

    public const TAG_PREFIX = 'funblockoneblock.';
    public const DEFAULT_TAGS = ['xp', 'level', 'leader', 'world', 'chat', 'level_name', 'island_name'];

    /**
     * @param TagsResolveEvent $event
     * @return void
     */
    public function onTagResolve(TagsResolveEvent $event): void
    {
        $tag = $event->getTag();
        $player = FunBlockOneBlock::getInstance()->getPlayerManager()->getPlayer($event->getPlayer());
        $oneBlock = FunBlockOneBlock::getInstance()->getOneBlockManager()->getOneBlockByUuid($player->getOneBlock());
        if (!$oneBlock instanceof OneBlock) {
            $tagsArray = [];
            foreach (self::DEFAULT_TAGS as $t)
                $tagsArray[] = new ScoreTag(self::TAG_PREFIX . $t, "N/A");
            $event = new PlayerTagsUpdateEvent($event->getPlayer(), $tagsArray);
            $event->call();;
            return;
        }
        switch ($tag->getName())
        {
            case self::TAG_PREFIX . 'xp':
                $tag->setValue((string)$oneBlock->getXp());
                break;
            case self::TAG_PREFIX . 'level_name':
                $tag->setValue($oneBlock->getLevel()->getName());
                break;
            case self::TAG_PREFIX . "island_name":
                $tag->setValue($oneBlock->getName());
                break;
            case self::TAG_PREFIX .  'level':
                $tag->setValue((string)$oneBlock->getLevel()->asInt());
                break;
            case self::TAG_PREFIX .  'leader':
                $tag->setValue($oneBlock->getLeader());
                break;
            case self::TAG_PREFIX .  'world':
                $tag->setValue($oneBlock->getWorld());
                break;
            case self::TAG_PREFIX .  'chat':
                $tag->setValue(FunBlockOneBlock::getInstance()->isChatting($event->getPlayer()));
                break;
        }
    }

    /**
     * @param XPChangeEvent $event
     * @return void
     */
    public function onXPChange(XPChangeEvent $event): void
    {
        $ev = new PlayerTagUpdateEvent($event->getPlayer(), new ScoreTag( self::TAG_PREFIX . 'xp', (string)$event->getXp()));
        $ev->call();
    }

    /**
     * @param LevelChangeEvent $event
     * @return void
     */
    public function onLevelChange(LevelChangeEvent $event): void
    {
        $ev = new PlayerTagsUpdateEvent(
            $event->getPlayer(), [
            new ScoreTag( self::TAG_PREFIX . 'level', (string)$event->getLevel()),
            new ScoreTag( self::TAG_PREFIX . 'level_name', $event->getLevelName())
        ]);
        $ev->call();
    }

    /**
     * @param DeleteEvent $event
     * @return void
     */
    public function onDelete(DeleteEvent $event): void
    {
        FunBlockOneBlock::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($event): void {
            $members = $event->getOneBlock()->getMembers();
            foreach ($members as $member)
            {
                $player = FunBlockOneBlock::getInstance()->getServer()->getPlayerByPrefix($member);
                if (!$player instanceof Player)
                    continue;
                $tagsArray = [];
                foreach (self::DEFAULT_TAGS as $t)
                    $tagsArray[] = new ScoreTag(self::TAG_PREFIX . $t, "N/A");
                $event = new PlayerTagsUpdateEvent($event->getPlayer(), $tagsArray);
                $event->call();
            }
        }), 20);

    }

    /**
     * @param CreationEvent $event
     * @return void
     */
    public function onCreate(CreationEvent $event): void
    {
        $oneblock = $event->getOneBlock();
        $level = $oneblock->getLevel()->asInt();
        $xp = $oneblock->getLevel()->asInt();
        $leader = $oneblock->getLeader();
        $world = $oneblock->getWorld();
        $chat = FunBlockOneBlock::getInstance()->isChatting($event->getPlayer());
        $level_name = $oneblock->getLevel()->getName();
        $island_name = $oneblock->getName();
        $tagArray = [];
        foreach (self::DEFAULT_TAGS as $tag)
        {
            $tagArray = new ScoreTag($tag, (string)${$tag});
        }
        $event = new PlayerTagsUpdateEvent($event->getPlayer(), $tagArray);
        $event->call();
    }

    /**
     * @param ChatToggleEvent $event
     * @return void
     */
    public function onChatToggle(ChatToggleEvent $event): void
    {
        $event= new PlayerTagUpdateEvent($event->getPlayer(), new ScoreTag( self::TAG_PREFIX . "chat", FunBlockOneBlock::getInstance()->isChatting($event->getPlayer())));
        $event->call();
    }

}
