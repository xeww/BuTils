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

final class FlySpeedCommand extends Command implements PluginOwned{

    public function __construct(){
        parent::__construct(
            $name = "flyspeed",
            "Update your flying speed",
            BuTils::PREFIX.TextFormat::RED."Use: /$name horizontal:[".BuTils::MIN_FLY_SPEED."-".BuTils::MAX_FLY_SPEED."] vertical:[0-any] | [reset/default]",
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
                $sender->sendMessage(BuTils::PREFIX.TextFormat::GREEN."Current vertical flying speed: ".TextFormat::WHITE.$session->getVerticalFlySpeed());
                $sender->sendMessage($this->getUsage());
                return;
            }

            if($args[0] === "default" || $args[0] === "reset"){
                $session->setFlySpeed(BuTils::DEFAULT_FLY_SPEED);
                $session->setVerticalFlySpeed(BuTils::DEFAULT_VERTICAL_FLY_SPEED);
                $sender->sendMessage(BuTils::PREFIX.TextFormat::GREEN."Flying speed was set back to default.");
                return;
            }

            if(!is_numeric($args[0])){
                $sender->sendMessage(BuTils::PREFIX.TextFormat::RED."Argument #0 (Horizontal flying speed) MUST be numeric.");
                return;
            }

            if(isset($args[1]) && !is_numeric($args[1])) {
                $sender->sendMessage(BuTils::PREFIX.TextFormat::RED."Argument #1 (Vertical flying speed) MUST be numeric");
                return;
            }

            $speed = floatval($args[0]);
            $verticalSpeed = isset($args[1]) ? floatval($args[1]) : null;

            if($speed < BuTils::MIN_FLY_SPEED || $speed > BuTils::MAX_FLY_SPEED){
                $sender->sendMessage(BuTils::PREFIX.TextFormat::RED."Fly speed shall be in range ".BuTils::MIN_FLY_SPEED." - ".BuTils::MAX_FLY_SPEED.".");
                return;
            }

            $session->setFlySpeed($speed);
            $sender->sendMessage(BuTils::PREFIX.TextFormat::GREEN."Flying speed set to: ".TextFormat::WHITE.$speed);
            if($verticalSpeed !== null) {
                $sender->sendMessage(BuTils::PREFIX.TextFormat::GREEN."Vertical flying speed set to: ".TextFormat::WHITE.$verticalSpeed);
                $session->setVerticalFlySpeed($verticalSpeed);
            }
        }
    }

    public function getOwningPlugin() : Plugin{
        return BuTils::getInstance();
    }
}