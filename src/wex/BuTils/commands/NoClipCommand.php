<?php

declare(strict_types=1);

namespace wex\BuTils\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use wex\BuTils\BuTils;

final class NoClipCommand extends Command{

    public function __construct(){
        parent::__construct("noclip",
            "Toggle no-clip",
            null,
            ["nc"]);
        $this->setPermission("butils.command.noclip");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
        if($this->testPermission($sender)){

            if(!$sender instanceof Player){
                $sender->sendMessage(BuTils::PREFIX.TextFormat::RED."This command may only be run in-game.");
                return;
            }

            if($sender->getGamemode() !== GameMode::CREATIVE){
                $sender->sendMessage(BuTils::PREFIX.TextFormat::RED."You may only use this command in creative mode.");
                return;
            }

            if($sender->hasBlockCollision()){

                $sender->setHasBlockCollision(false);
                $sender->sendMessage(BuTils::PREFIX.TextFormat::GREEN."Enabled No-Clip.");

            }else{

                $sender->setHasBlockCollision(true);
                $sender->sendMessage(BuTils::PREFIX.TextFormat::GREEN."Disabled No-Clip.");
            }
        }
    }
}