<?php

declare(strict_types=1);

namespace pmmlp\crafting;

use pocketmine\crafting\CraftingRecipe;
use pocketmine\crafting\ShapedRecipe;
use pocketmine\crafting\ShapelessRecipe;
use pocketmine\Server;

final class CraftingRecipeRegistry {
    public static function registerRecipe(CraftingRecipe $recipe): void {
        if($recipe instanceof ShapedRecipe) {
            Server::getInstance()->getCraftingManager()->registerShapedRecipe($recipe);
        } elseif($recipe instanceof ShapelessRecipe) {
            Server::getInstance()->getCraftingManager()->registerShapelessRecipe($recipe);
        }
    }
}