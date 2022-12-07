<?php

declare(strict_types=1);

namespace pmmlp\config;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\VersionString;
use ReflectionClass;
use ReflectionProperty;

abstract class Config {
    public function __construct(PluginBase $plugin){
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_STATIC);
        $config = $plugin->getConfig();
        $path = $config->getPath();
        $logger = $plugin->getLogger();


        //Auto Updater
        $pluginVersion = new VersionString(self::$version);
        if(!$config->exists("version") || $pluginVersion->compare(new VersionString($config->get("version")), true) > 0) {
            $logger->warning("Plugin config is not up-to-date. Config will be updated to V".self::$version);

            rename($path, $path."_old");

            $config->setAll([]);
            foreach($properties as $property) {
                if(!$property->isPublic() || !$property->isStatic()) {
                    continue;
                }
                $config->set($property->getName(), $property->getValue());
            }
            $config->save();
        }

        //Load data from config
        foreach($properties as $property) {
            if(!$property->isPublic() || !$property->isStatic()) {
                continue;
            }
            $propertyName = $property->getName();
            $this::${$propertyName} = $config->get($propertyName);
        }

        $logger->debug("Successfully loaded config!");
    }

    public static string $version = "1.0.0";
}