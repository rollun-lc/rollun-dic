<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 27.12.16
 * Time: 5:47 PM
 */

namespace zaboy\skeleton;


use Composer\Script\Event;
use zaboy\installer\Install\AbstractCommand;
use zaboy\installer\Install\InstallerInterface;

class InstallCommands extends AbstractCommand
{
    /**
     * @param null $dir
     * @return InstallerInterface[]
     */
    public static function getInstallers($dir = null)
    {
        if (!isset($dir)) {
            $dir = __DIR__;
        }
        return parent::getInstallers($dir);
    }
}