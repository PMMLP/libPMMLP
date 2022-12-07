<?php

declare(strict_types=1);

namespace pmmlp\resourcepack;

use pocketmine\plugin\PharPluginLoader;
use pocketmine\plugin\PluginBase;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\Server;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use SplFileInfo;
use ZipArchive;

class ResourcePackLoader {
    public function __construct(PluginBase $plugin, string $pluginFile){
        $manager = Server::getInstance()->getResourcePackManager();
        $reflection = new ReflectionClass($manager);

        $root = $pluginFile."resource_pack";
        $resourcePackPath = Server::getInstance()->getDataPath()."resource_packs/";

        if(!$plugin->getPluginLoader() instanceof PharPluginLoader) {
            $zipFile = $resourcePackPath.$plugin->getName().".zip";

            $zip = new ZipArchive();
            $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            /** @var SplFileInfo[] $files */
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($root."/"),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                if (!$file->isDir() && !str_ends_with($name, ".zip")) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($root) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        } elseif(!is_file($zipFile = $resourcePackPath.$plugin->getName().".zip")) {
            $zipFile = $resourcePackPath.$plugin->getName().".mcpack";
        }

        $pack = new ZippedResourcePack($zipFile);

        $packsProperty = $reflection->getProperty("resourcePacks");
        $packsProperty->setAccessible(true);
        $currentResourcePacks = $packsProperty->getValue($manager);

        $property = $reflection->getProperty("serverForceResources");
        $property->setAccessible(true);
        $property->setValue($manager, true);

        $uuid = $reflection->getProperty("uuidList");
        $uuid->setAccessible(true);
        $packs = $uuid->getValue($manager);

        $packs[strtolower($pack->getPackId())] = $currentResourcePacks[] = $pack;

        $packsProperty->setValue($manager, $currentResourcePacks);
        $uuid->setValue($manager, $packs);
    }
}