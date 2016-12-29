<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 27.12.16
 * Time: 4:04 PM
 */

use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Glob;
$cachedConfigFile = 'data/cache/app_config.php';
$config = [];
if (is_file($cachedConfigFile)) {
    // Try to load the cached config
    $config = json_decode(file_get_contents($cachedConfigFile), true);
} else {
    // Load configuration from autoload path
    foreach (Glob::glob('config/autoload/{{,*.}global,{,*.}local}.php', Glob::GLOB_BRACE) as $file) {
        $config = ArrayUtils::merge($config, include $file);
    }
    // Cache config if enabled
    if (isset($config['config_cache_enabled']) && $config['config_cache_enabled'] === true) {
        file_put_contents($cachedConfigFile, json_encode($config));
    }
}
// Return an ArrayObject so we can inject the config as a service in Aura.Di
// and still use array checks like ``is_array``.
return new ArrayObject($config, ArrayObject::ARRAY_AS_PROPS);