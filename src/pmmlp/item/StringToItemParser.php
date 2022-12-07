<?php

declare(strict_types=1);

namespace pmmlp\item;

use customiesdevs\customies\item\CustomiesItemFactory;
use Exception;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

class StringToItemParser {
    public static function parse(string $data): ?Item {
        $array = explode("::", $data);


        if(!is_numeric($array[0]) || !ItemFactory::getInstance()->isRegistered((int)$array[0])) {
            $item = CustomiesItemFactory::getInstance()->get($array[0], (int)($array[2] ?? 1));
        } else {
            $item = ItemFactory::getInstance()->get((int)$array[0], (int)($array[1] ?? 0), (int)($array[2] ?? 1));
        }
        try{
            //$json = json_decode($array[2], true, 512, JSON_THROW_ON_ERROR);

            //TODO: Custom Name
            //TODO: Enchantments
            //TODO: Lore
        } catch(Exception $exception) {}
        return $item;
    }
}