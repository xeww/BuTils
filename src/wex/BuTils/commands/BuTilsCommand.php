<?php

declare(strict_types=1);

namespace wex\BuTils\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;
use wex\BuTils\BuTils;
use wex\BuTils\forms\overview\OverviewForm;

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

            if(!$sender instanceof Player){
                $sender->sendMessage(BuTils::PREFIX.TextFormat::RED."This command may only be run in-game.");
                return;
            }

            OverviewForm::openForm($sender);
        }
    }

    public function getOwningPlugin() : Plugin{
        return BuTils::getInstance();
    }
}