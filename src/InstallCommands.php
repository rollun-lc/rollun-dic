<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 27.12.16
 * Time: 5:47 PM
 */

namespace installer;


use Composer\Script\Event;
use zaboy\installer\Instal\AbstractCommand;

class InstallCommands extends AbstractCommand
{

    /**
     * return array with Install class for lib;
     * @return array
     */
    public static function getInstallers()
    {
        // TODO: Implement getInstaller() method.
        return [

        ];
    }

    /**
     * @param Event $event
     */
    public static function install(Event $event)
    {
        parent::command($event, parent::INSTALL, self::getInstallers());
    }

    /**
     * @param Event $event
     */
    public static function uninstall(Event $event)
    {
        parent::command($event, parent::UNINSTALL, self::getInstallers());
    }

    /**
     * @param Event $event
     */
    public static function reinstall(Event $event)
    {
        parent::command($event, parent::REINSTALL, self::getInstallers());
    }
}