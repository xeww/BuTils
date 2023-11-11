<?php

declare(strict_types=1);

namespace wex\BuTils;

use pocketmine\network\mcpe\protocol\types\AbilitiesData;
use pocketmine\network\mcpe\protocol\types\AbilitiesLayer;
use pocketmine\network\mcpe\protocol\types\command\CommandPermissions;
use pocketmine\network\mcpe\protocol\types\PlayerPermissions;
use pocketmine\network\mcpe\protocol\UpdateAbilitiesPacket;
use pocketmine\permission\DefaultPermissionNames;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\ClosureTask;

final class BuTilsSession{

    private Player $player;

    private float $flySpeed = BuTils::DEFAULT_FLY_SPEED;

    public function __construct(Player $player){
        $this->player = $player;
        $this->check();
    }

    public function getPlayer() : Player{
        return $this->player;
    }

    private function check() : void{
        BuTils::getInstance()->getScheduler()->scheduleRepeatingTask(new ClosureTask(function() : void{
            if(!$this->getPlayer()->isConnected()){
                throw new CancelTaskException();
            }

            $this->updateAbilities();
        }), 20);
    }

    public function getFlySpeed() : float{
        return $this->flySpeed;
    }

    public function setFlySpeed(float $flySpeed) : void{
        $this->flySpeed = $flySpeed;
    }

    public function updateAbilities() : void{
        $player = $this->getPlayer();

        if($player->isConnected()){

            $isOp = $player->hasPermission(DefaultPermissions::ROOT_OPERATOR);

            $boolAbilities = [
                AbilitiesLayer::ABILITY_ALLOW_FLIGHT => $player->getAllowFlight(),
                AbilitiesLayer::ABILITY_FLYING => $player->isFlying(),
                AbilitiesLayer::ABILITY_NO_CLIP => !$player->hasBlockCollision(),
                AbilitiesLayer::ABILITY_OPERATOR => $isOp,
                AbilitiesLayer::ABILITY_TELEPORT => $player->hasPermission(DefaultPermissionNames::COMMAND_TELEPORT_SELF),
                AbilitiesLayer::ABILITY_INVULNERABLE => $player->isCreative(),
                AbilitiesLayer::ABILITY_MUTED => false,
                AbilitiesLayer::ABILITY_WORLD_BUILDER => false,
                AbilitiesLayer::ABILITY_INFINITE_RESOURCES => !$player->hasFiniteResources(),
                AbilitiesLayer::ABILITY_LIGHTNING => false,
                AbilitiesLayer::ABILITY_BUILD => !$player->isSpectator(),
                AbilitiesLayer::ABILITY_MINE => !$player->isSpectator(),
                AbilitiesLayer::ABILITY_DOORS_AND_SWITCHES => !$player->isSpectator(),
                AbilitiesLayer::ABILITY_OPEN_CONTAINERS => !$player->isSpectator(),
                AbilitiesLayer::ABILITY_ATTACK_PLAYERS => !$player->isSpectator(),
                AbilitiesLayer::ABILITY_ATTACK_MOBS => !$player->isSpectator(),
                AbilitiesLayer::ABILITY_PRIVILEGED_BUILDER => false,
            ];

            $layers = [
                new AbilitiesLayer(AbilitiesLayer::LAYER_BASE, $boolAbilities, $this->getFlySpeed(), 0.1),
            ];

            if(!$player->hasBlockCollision()){
                $layers[] = new AbilitiesLayer(AbilitiesLayer::LAYER_SPECTATOR, [
                    AbilitiesLayer::ABILITY_FLYING => true,
                ], null, null);
            }

            $player->getNetworkSession()->sendDataPacket(UpdateAbilitiesPacket::create(new AbilitiesData(
                $isOp ? CommandPermissions::OPERATOR : CommandPermissions::NORMAL,
                $isOp ? PlayerPermissions::OPERATOR : PlayerPermissions::MEMBER,
                $player->getId(),
                $layers
            )));
        }
    }
}