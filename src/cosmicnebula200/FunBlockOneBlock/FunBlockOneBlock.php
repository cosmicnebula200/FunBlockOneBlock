<?php

declare(strict_types=1);

namespace cosmicnebula200\FunBlockOneBlock;

use CortexPE\Commando\PacketHooker;
use cosmicnebula200\FunBlockOneBlock\commands\OneBlockCommand;
use cosmicnebula200\FunBlockOneBlock\generator\Generator;
use cosmicnebula200\FunBlockOneBlock\invites\InviteManager;
use cosmicnebula200\FunBlockOneBlock\level\LevelManager;
use cosmicnebula200\FunBlockOneBlock\listener\EventListener;
use cosmicnebula200\FunBlockOneBlock\messages\Messages;
use cosmicnebula200\FunBlockOneBlock\oneblock\OneBlockManager;
use cosmicnebula200\FunBlockOneBlock\player\Player;
use cosmicnebula200\FunBlockOneBlock\player\PlayerManager;
use pocketmine\plugin\PluginBase;
use pocketmine\player\Player as P;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;

class FunBlockOneBlock extends PluginBase
{

    /** @var DataConnector */
    private DataConnector $dataConnector;
    /** @var Generator */
    private Generator $generator;
    /** @var Messages */
    private Messages $messages;
    /** @var P[] */
    private array $chat;
    /** @var PlayerManager */
    private PlayerManager $playerManager;
    /** @var OneBlockManager */
    private OneBlockManager $oneBlockManager;
    /** @var LevelManager */
    private LevelManager $levelManager;
    /** @var InviteManager */
    private InviteManager $inviteManager;
    /** @var FunBlockOneBlock */
    private static self $instance;

    protected function onLoad(): void
    {
        self::$instance = $this;
    }

    public function onEnable(): void
    {
        if (!PacketHooker::isRegistered())
            PacketHooker::register($this);
        $this->saveDefaultConfig();
        $this->saveResource('levels.yml');
        $this->saveResource('messages.yml');
        $this->saveResource('forms.yml');
        $this->initDataBase();
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->chat = [];
        $this->generator = new Generator();
        $this->messages = new Messages();
        $this->playerManager = new PlayerManager();
        $this->oneBlockManager = new OneBlockManager();
        $this->levelManager = new LevelManager();
        $this->inviteManager = new InviteManager();
        $this->getServer()->getCommandMap()->register('FunBlockOneBlock', new OneBlockCommand($this, 'oneblock', 'the basecommand for FunBlockOneBlock', ['ob']));
    }

    public function onDisable(): void
    {
        $this->dataConnector->waitAll();
        $this->dataConnector->close();
    }

    public function initDataBase(): void
    {
        $db = libasynql::create($this, $this->getConfig()->get('database'), ['mysql' => 'mysql.sql', 'sqlite' => 'sqlite.sql']);
        $db->executeGeneric('funblockoneblock.player.init');
        $db->executeGeneric('funblockoneblock.oneblock.init');
        $db->waitAll();
        $this->dataConnector = $db;
    }

    /**
     * @return P[]
     */
    public function getChat(): array
    {
        return $this->chat;
    }

    public function addPlayerToChat(P $player): void
    {
        $this->chat[] = $player;
    }

    public function removePlayerFromChat(P $player): void
    {
        unset($this->chat[array_search($player->getName(), $this->chat)]);
    }

    /**
     * @return DataConnector
     */
    public function getDataBase(): DataConnector
    {
        return $this->dataConnector;
    }

    /**
     * @return Generator
     */
    public function getGenerator(): Generator
    {
        return $this->generator;
    }

    /**
     * @return Messages
     */
    public function getMessages(): Messages
    {
        return $this->messages;
    }

    /**
     * @return PlayerManager
     */
    public function getPlayerManager(): PlayerManager
    {
        return $this->playerManager;
    }

    /**
     * @return OneBlockManager
     */
    public function getOneBlockManager(): OneBlockManager
    {
        return $this->oneBlockManager;
    }

    /**
     * @return LevelManager
     */
    public function getLevelManager(): LevelManager
    {
        return $this->levelManager;
    }

    /**
     * @return InviteManager
     */
    public function getInviteManager(): InviteManager
    {
        return $this->inviteManager;
    }

    /**
     * @return self
    */
    public static function getInstance(): self
    {
        return self::$instance;
    }

}
