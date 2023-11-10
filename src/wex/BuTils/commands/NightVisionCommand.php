<?php

declare(strict_types=1);

namespace wex\BuTils\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\Limits;
use pocketmine\utils\TextFormat;
use wex\BuTils\BuTils;

final class NightVisionCommand extends Command implements PluginOwned{

    public function __construct(){
        parent::__construct(
            "nightvision",
            "Toggle night vision",
            null,
            ["nv"]
        );
        $this->setPermission("butils.command.nightvision");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
        if($this->testPermission($sender)){

            if(!$sender instanceof Player){
                $sender->sendMessage(BuTils::PREFIX.TextFormat::RED."This command may only be run in-game.");
                return;
            }

            $effects = $sender->getEffects();
            $nightVision = VanillaEffects::NIGHT_VISION();

            if($effects->has($nightVision)){

                $effects->remove($nightVision);
                $sender->sendMessage(BuTils::PREFIX.TextFormat::GREEN."Toggled night vision:".TextFormat::DARK_RED." OFF");

            }else{

                $effects->add(new EffectInstance($nightVision, Limits::INT32_MAX, 255, false));
                $sender->sendMessage(BuTils::PREFIX.TextFormat::GREEN."Toggled night vision;".TextFormat::DARK_GREEN." ON");
            }
        }
    }

    public function getOwningPlugin() : Plugin{
        return BuTils::getInstance();
    }
}