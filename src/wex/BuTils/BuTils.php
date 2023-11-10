<?php

declare(strict_types=1);

namespace wex\BuTils;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use wex\BuTils\commands\BuTilsCommand;
use wex\BuTils\commands\FlySpeedCommand;
use wex\BuTils\commands\NightVisionCommand;
use wex\BuTils\commands\NoClipCommand;

final class BuTils extends PluginBase{

    public const PREFIX =
        TextFormat::WHITE."[".TextFormat::GOLD."Bu".TextFormat::GRAY.TextFormat::ITALIC."Tils".TextFormat::RESET."] ".TextFormat::GRAY.TextFormat::BOLD."» ".TextFormat::RESET;

    public const DEFAULT_FLY_SPEED = 0.05;

    public const MIN_FLY_SPEED = 0.01;

    // maximum safest value to avoid issues
    public const MAX_FLY_SPEED = 0.70;

    private static self $instance;

    /**
     * @var array<string, BuTilsSession>
     */
    private array $sessions = [];

    public function onEnable() : void{
        self::$instance = $this;
        $this->saveDefaultConfig();
        $this->registerListeners();
        $this->registerCommands();
    }

    private function registerListeners() : void{
        $pluginManager = $this->getServer()->getPluginManager();
        $pluginManager->registerEvents(new BuTilsListener(), $this);
    }

    private function registerCommands() : void{
        $commandMap = $this->getServer()->getCommandMap();
        $commandMap->registerAll($this->getName(), [
            new BuTilsCommand(),
            new FlySpeedCommand(),
            new NoClipCommand(),
            new NightVisionCommand()
        ]);
    }

    public static function getInstance() : self{
        return self::$instance;
    }

    public function hasExplosions() : bool{
        return (bool)$this->getConfig()->get("explosions", true);
    }

    public function hasLeavesDecay() : bool{
        return (bool)$this->getConfig()->get("leaves_decay", true);
    }

    public function doesDragonEggTeleports() : bool{
        return (bool)$this->getConfig()->get("dragon_egg_teleport", true);
    }

    public function addSession(Player $player) : void{
        $this->sessions[$player->getName()] = new BuTilsSession($player);
    }

    public function removeSession(Player $player) : void{
        unset($this->sessions[$player->getName()]);
    }

    public function getSession(Player $player) : ?BuTilsSession{
        return $this->sessions[$player->getName()] ?? null;
    }
}