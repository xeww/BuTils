<?php

declare(strict_types=1);

namespace wex\BuTils\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use wex\BuTils\BuTils;

final class BuTilsCommand extends Command implements PluginOwned{

    public function __construct(){
        parent::__construct(
            "butils",
            "BuTils overview command"
        );
        $this->setPermission("butils.command.butils");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
        if($this->testPermission($sender)){

        }
    }

    public function getOwningPlugin() : Plugin{
        return BuTils::getInstance();
    }
}