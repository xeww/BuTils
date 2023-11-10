<?php

declare(strict_types=1);

namespace wex\BuTils\forms\overview\sub;

use dktapps\pmforms\CustomForm;
use pocketmine\player\Player;

final class CommandsForm{

    public static function openForm(Player $player) : void{
        $form = new CustomForm(
            "Commands",
            [],
            function(){}, function(){});
        $player->sendForm($form);
    }
}