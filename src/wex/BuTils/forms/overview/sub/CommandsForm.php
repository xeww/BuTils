<?php

declare(strict_types=1);

namespace wex\BuTils\forms\overview\sub;

use dktapps\pmforms\MenuForm;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat as TF;
use wex\BuTils\BuTils;

final class CommandsForm{

    public static function openForm(Player $player) : void{
        $text = "";
        $plugin = BuTils::getInstance();

        /** @var array<int, string> $commands */
        $commands = [];
        foreach($plugin->getServer()->getCommandMap()->getCommands() as $command){

            if($command instanceof PluginOwned){

                if($command->getOwningPlugin() instanceof BuTils){

                    $commandName = $command->getName();
                    $commandAliases = $command->getAliases();
                    $description = $command->getDescription();

                    if(!in_array($commandName, $commands)){
                        $commands[] = $commandName;
                        $text .= TF::GRAY.TF::BOLD."» ".TF::RESET."/$commandName";

                        if(!empty($commandAliases)){
                            $text .= " (".implode(", ", $commandAliases).")";
                        }

                        if(is_string($description)){
                            $text .= "\n".TF::GRAY.TF::ITALIC.$description."\n\n".TF::RESET;
                        }
                    }
                }
            }
        }

        $form = new MenuForm(
            "Commands", $text, [],

            function(Player $player, int $selectedOption) : void{

            }
        );
        $player->sendForm($form);
    }
}