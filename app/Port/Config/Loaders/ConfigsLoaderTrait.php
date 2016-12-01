<?php

namespace App\Port\Config\Loaders;

use App;
use App\Port\Butler\Portals\Facade\PortButler;
use DB;
use File;

/**
 * Class ConfigsLoaderTrait.
 *
 * @author  Mahmoud Zalt <mahmoud@zalt.me>
 */
trait ConfigsLoaderTrait
{

    protected $portConfigsDirectories = [
        'Config/Configs'
    ];

    public function autoLoadConfigFiles()
    {
        $this->autoLoadPortConfigFiles();
        $this->autoLoadContainersConfigFiles();
    }

    protected function autoLoadPortConfigFiles()
    {
        foreach ($this->portConfigsDirectories as $portConfigsDirectory) {
            $this->loadConfigs(base_path('app/Port/') . $portConfigsDirectory);
        }
    }

    protected function autoLoadContainersConfigFiles()
    {
        foreach (PortButler::getContainersNames() as $containerName) {
            $this->loadConfigs(base_path('app/Containers/' . $containerName . '/Configs'));
        }
    }

    private function loadConfigs($directory)
    {
        if (File::isDirectory($directory)) {

            $files = File::allFiles($directory);

            foreach ($files as $file) {
                // build the key from the file name (just remove the .php extension from the file name)
                $fileNameOnly = str_replace('.php', '', $file->getFilename());

                // merge the config file
                $this->mergeConfigFrom($file->getPathname(), $fileNameOnly);
            }
        }
    }
}
