<?php

declare(strict_types=1);

namespace wex\BuTils\forms\overview\sub;

use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Label;
use dktapps\pmforms\element\Toggle;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use wex\BuTils\BuTils;

final class GlobalParametersForm{

    public static function openForm(Player $player) : void{
        $plugin = BuTils::getInstance();
        $config = $plugin->getConfig();

        $form = new CustomForm(

            "Global Parameters",
            [
                new Label(uniqid(), TextFormat::GRAY."Allows to completely disable explosions."),
                new Toggle("explosions", "» Enable explosions ?", $plugin->hasExplosions()),

                new Label(uniqid(), TextFormat::GRAY."Allows leaves to remain without decaying."),
                new Toggle("leaves_decay", "» Enable leaves decay ?", $plugin->hasLeavesDecay()),

                new Label(uniqid(), TextFormat::GRAY."The dragon egg will teleport upon interacting with it, this toggle disables that behaviour."),
                new Toggle("dragon_egg_teleport", "» Enable dragon egg teleport ?", $plugin->doesDragonEggTeleports()),

                new Label(uniqid(), TextFormat::GRAY."Removes the gravity from any falling blocks (e.g. anvil, sand, etc)."),
                new Toggle("falling_blocks", "» Enable falling blocks ?", $plugin->hasFallingBlocks()),

                new Label(uniqid(), TextFormat::GRAY."Corals as well as coral blocks dies outside of water, this toggle disables that behaviour."),
                new Toggle("coral_death", "» Enable coral death ?", $plugin->hasCoralDeath()),

                new Label(uniqid(), TextFormat::GRAY."Allows liquid such as lava and water to not flow."),
                new Toggle("liquid_flow", "» Enable liquid flow ?", $plugin->hasLiquidFlow())
            ],

            function(Player $submitter, CustomFormResponse $response) use($config) : void{
                $config->set("explosions", $response->getBool("explosions"));
                $config->set("leaves_decay", $response->getBool("leaves_decay"));
                $config->set("dragon_egg_teleport", $response->getBool("dragon_egg_teleport"));
                $config->set("falling_blocks", $response->getBool("falling_blocks"));
                $config->set("coral_death", $response->getBool("coral_death"));
                $config->set("liquid_flow", $response->getBool("liquid_flow"));
                $config->save();

                $submitter->sendMessage(BuTils::PREFIX.TextFormat::GREEN."Saved global parameters.");
            },

            function(Player $submitter) : void{
            }
        );
        $player->sendForm($form);
    }
}