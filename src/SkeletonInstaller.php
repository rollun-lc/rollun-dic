<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 13.01.17
 * Time: 12:59
 */

namespace rollun\skeleton;

use rollun\installer\Command;
use rollun\installer\Install\InstallerAbstract;

class SkeletonInstaller extends InstallerAbstract
{

    /**
     * install
     * @return void
     */
    public function install()
    {
        $dataDir = Command::getDataDir();
        if (isset($dataDir) && is_dir($dataDir)) {
            chmod($dataDir, 766);
        } else {
            $this->io->write("Data dir '$dataDir' not fount. try to create...");
            try {
                mkdir($dataDir, 766, true);
            } catch (\Error $error) {
                $this->io->writeError("Data dir $dataDir not created and don't have permission to create.");
            }
        }
    }

    /**
     * Clean all installation
     * @return void
     */
    public function uninstall()
    {

    }
}
