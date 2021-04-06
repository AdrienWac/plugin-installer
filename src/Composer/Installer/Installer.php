<?php

namespace Composer\Installer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class Installer extends LibraryInstaller
{

    public function getInstallPath(PackageInterface $package)
    {

        $package->getExtra();

        $value = $package->getName();
        $value = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $value));
        $value = str_replace(array('-', '_'), ' ', $value);
        $value = str_replace(' ', '', ucwords($value));
        
        return 'Plugin/'.$value;

    }

}