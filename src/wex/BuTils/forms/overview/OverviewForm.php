<?php

declare(strict_types=1);

namespace wex\BuTils\forms\overview;

use dktapps\pmforms\FormIcon;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use wex\BuTils\BuTils;
use wex\BuTils\forms\overview\sub\CommandsForm;
use wex\BuTils\forms\overview\sub\GlobalParametersForm;

final class OverviewForm{

    public static function openForm(Player $player) : void{
        $form = new MenuForm(
            "Overview",
            TextFormat::GREEN.BuTils::getInstance()->getDescription()->getFullName(),

            [
                new MenuOption("» Global Parameters «", new FormIcon("textures/ui/icon_setting", FormIcon::IMAGE_TYPE_PATH)),
                new MenuOption("» Commands «", new FormIcon("textures/ui/creator_glyph_color", FormIcon::IMAGE_TYPE_PATH)),
            ],

            function(Player $submitter, int $selected) : void{
                match($selected){
                    0 => GlobalParametersForm::openForm($submitter),
                    1 => CommandsForm::openForm($submitter),
                    default => null
                };
            },

            function(Player $submitter) : void{
            }
        );
        $player->sendForm($form);
    }
}