<?php

declare(strict_types=1);

namespace wex\BuTils\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use wex\BuTils\BuTils;

final class FlySpeedCommand extends Command{

    public function __construct(){
        parent::__construct(
            $name = "flyspeed",
            "Update your flying speed",
            BuTils::PREFIX.TextFormat::RED."Use: /$name [".BuTils::MIN_FLY_SPEED."-".BuTils::MAX_FLY_SPEED."]|[reset/default]",
            ["fs", "fspeed"]
        );
        $this->setPermission("butils.command.flyspeed");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
        if($this->testPermission($sender)){

            if(!$sender instanceof Player){
                $sender->sendMessage(BuTils::PREFIX.TextFormat::RED."This command may only be run in-game.");
                return;
            }

            $session = BuTils::getInstance()->getSession($sender);

            if(is_null($session)){
                $sender->sendMessage(BuTils::PREFIX.TextFormat::RED."Session is null.");
                return;
            }

            if(!isset($args[0])){
                $sender->sendMessage(BuTils::PREFIX.TextFormat::GREEN."Current flying speed: ".TextFormat::WHITE.$session->getFlySpeed());
                $sender->sendMessage($this->getUsage());
                return;
            }

            if($args[0] === "default" || $args[0] === "reset"){
                $session->setFlySpeed(BuTils::DEFAULT_FLY_SPEED);
                $sender->sendMessage(BuTils::PREFIX.TextFormat::GREEN."§aFlying speed was set back to default.");
                return;
            }

            if(!is_numeric($args[0])){
                $sender->sendMessage(BuTils::PREFIX.TextFormat::RED."Argument #0 MUST be numeric.");
                return;
            }

            $speed = floatval($args[0]);

            if($speed < BuTils::MIN_FLY_SPEED || $speed > BuTils::MAX_FLY_SPEED){
                $sender->sendMessage(BuTils::PREFIX.TextFormat::RED."Fly speed shall be in range ".BuTils::MIN_FLY_SPEED." - ".BuTils::MAX_FLY_SPEED.".");
                return;
            }

            $session->setFlySpeed($speed);
            $sender->sendMessage(BuTils::PREFIX.TextFormat::GREEN."Flying speed set to: ".TextFormat::WHITE.$speed);
        }
    }
}