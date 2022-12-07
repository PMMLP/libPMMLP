<?php

declare(strict_types=1);

namespace pmmlp\config;

use pmmlp\item\StringToItemParser;
use pocketmine\crafting\CraftingRecipe;
use pocketmine\crafting\ShapedRecipe;
use pocketmine\crafting\ShapelessRecipe;

class ConfigParser {
    public static function getCraftingRecipe(array $recipe): CraftingRecipe {
        $ingredients = [];
        foreach($recipe["items"] as $key => $stringItem) {
            $ingredients[$key] = StringToItemParser::parse($stringItem);
        }
        $result = [StringToItemParser::parse($recipe["result"])];
        if($recipe["shaped"]) {
            return new ShapedRecipe($recipe["shape"], $ingredients, $result);
        }
        return new ShapelessRecipe($ingredients, $result);
    }
}