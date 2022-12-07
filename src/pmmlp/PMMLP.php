<?php

declare(strict_types=1);

namespace pmmlp;

use pocketmine\plugin\PluginBase;

class PMMLP extends PluginBase {
    protected function onLoad(): void{
        $this->getLogger()->info("Thank you for using PocketMine-Mod-Like-Plugins! You can support our project here => LINK");
    }
}