<?php

declare(strict_types=1);

namespace wex\BuTils\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

final class BuTilsCommand extends Command{

    public function __construct(){
        parent::__construct(
            "butils",
            "BuTils overview command"
        );
        $this->setPermission("butils.command.butils");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void{

    }
}