<?php

declare(strict_types=1);

namespace wex\BuTils;

use pocketmine\block\BlockTypeIds;
use pocketmine\event\block\LeavesDecayEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerGameModeChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\GameMode;
use pocketmine\utils\TextFormat;

final class BuTilsListener implements Listener{

    public function onPlayerJoin(PlayerJoinEvent $event) : void{
        BuTils::getInstance()->addSession($event->getPlayer());
    }

    public function onPlayerQuit(PlayerQuitEvent $event) : void{
        BuTils::getInstance()->removeSession($event->getPlayer());
    }

    public function onPlayerGameModeChange(PlayerGameModeChangeEvent $event) : void{
        $player = $event->getPlayer();
        $gameMode = $event->getNewGamemode();

        if($gameMode !== GameMode::CREATIVE){

            if(!$player->hasBlockCollision()){

                $player->sendMessage(BuTils::PREFIX.TextFormat::RED."No-Clip was disabled due to a gamemode change.");
                $player->setHasBlockCollision(true);
            }
        }
    }

    public function onPlayerInteract(PlayerInteractEvent $event) : void{
        if(!BuTils::getInstance()->doesDragonEggTeleports()){
            if($event->getBlock()->getTypeId() === BlockTypeIds::DRAGON_EGG){
                $event->cancel();
            }
        }
    }

    public function onEntityExplode(EntityExplodeEvent $event) : void{
        if(!BuTils::getInstance()->hasExplosions()){
            $event->cancel();
        }
    }

    public function onLeavesDecay(LeavesDecayEvent $event) : void{
        if(!BuTils::getInstance()->hasLeavesDecay()){
            $event->cancel();
        }
    }
}