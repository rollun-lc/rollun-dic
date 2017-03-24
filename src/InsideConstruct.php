<?php

/**
 * Zaboy lib (http://zaboy.org/lib/)
 *
 * @copyright  Zaboychenko Andrey
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace rollun\dic;

use Interop\Container\ContainerInterface;

class InsideConstruct
{

    /**
     * Use next in head af scripts
     * <code>
     * require 'vendor/autoload.php';
     * $container = include 'config/container.php';
     * //add:
     * InsideConstruct::setContainer( $container )
     * <code>
     *
     * @var ContainerInterface
     */
    protected static $container = null;

    /**
     * @return array
     */
    public static function setConstructParams(array $setService = [])
    {
        $result = [];

        InsideConstruct::checkContainer();

        //Who call me?;
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        $className = $trace[1]['class'];
        $reflectionClass = new \ReflectionClass($className);
        /* @var $reflectionClass \ReflectionClass */
        $object = $trace[1]['object'];
        $args = $trace[1]['args'];

        //I need your __construct params
        //$reflectionClass->getMethod('__construct');
        InsideConstruct::checkConstruct(($refConstruct = $reflectionClass->getConstructor()));

        //add service who not set in constructor
        $refParams = $refConstruct->getParameters();
        // $refParams array of ReflectionParameter
        foreach ($refParams as $refParam) {
            /* @var $refParam \ReflectionParameter */
            $paramName = $refParam->getName();
            //Is param retrived?
            if (empty($args)) {
                //Do this param need in service loading
                //if service mapped
                if (array_key_exists($paramName, $setService)) {
                    $serviceName = $setService[$paramName];
                    unset($setService[$paramName]);
                } else {
                    $serviceName = $paramName;
                }
                //Has service in $container?
                $paramValue = self::getParamValue($serviceName, $refParam);
            } else {
                //Value for param was retrived in __construct().
                $paramValue = array_shift($args);
            }
            $result[$paramName] = $paramValue;
            InsideConstruct::setValue($reflectionClass, $paramName, $paramValue, $object);
        }
        //set params to setter
        foreach ($setService as $paramName => $service) {
            if (static::$container->has($service)) {
                $paramValue = static::$container->get($service);
                $result[$paramName] = $paramValue;
                InsideConstruct::setValue($reflectionClass, $paramName, $paramValue, $object);
            }
        }
        return $result;
    }

    /**
     *
     */
    protected static function checkContainer()
    {
        global $container;
        static::$container = static::$container ? static::$container : $container;
        if (!(isset(static::$container) && static::$container instanceof ContainerInterface)) {
            throw new \UnexpectedValueException(
                'global $contaner or InsideConstruct::$contaner'
                . ' must be inited'
            );
        }
    }

    /**
     * @param \ReflectionMethod|null $refConstruct
     */
    protected static function checkConstruct(\ReflectionMethod $refConstruct = null)
    {
        //!проверяет наличие метода construct, а не нахождения в нем
        if (!isset($refConstruct)) {
            throw new \LengthException(
                'You must call InsideConstruct::initServices() inside Construct only'
            );
        }
    }

    /**
     * @param $paramName
     * @param $refParam
     * @return mixed
     */
    protected static function getParamValue($paramName, \ReflectionParameter $refParam)
    {
        if (static::$container->has($paramName)) {
            $paramValue = static::$container->get($paramName); // >getType()
            $paramClass = $refParam->getClass() ? $refParam->getClass()->getName() : null;
            if ($paramClass && !($paramValue instanceof $paramClass)) {
                throw new \LogicException(
                    'Wrong type for service: ' . $paramName
                );
            }
        } else {
            $paramValue = $refParam->getDefaultValue();
        }
        return $paramValue;
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param $paramName
     * @param $paramValue
     * @param $object
     */
    protected static function setValue(\ReflectionClass $reflectionClass, $paramName, $paramValue, $object)
    {
        //setters
        $methodName = 'set' . ucfirst($paramName);
        $refMethod = $reflectionClass->hasMethod($methodName) ?
            $reflectionClass->getMethod($methodName) :
            null;
        //properties
        $refProperty = $reflectionClass->hasProperty($paramName) ?
            $reflectionClass->getProperty($paramName) :
            null;

        if (isset($refMethod) && static::checkSetter($refMethod) && $refMethod->isPublic()) {
            $refMethod->invoke($object, $paramValue);
        } elseif (
            isset($refMethod)
            && static::checkSetter($refMethod)
            && ($refMethod->isPrivate() || $refMethod->isProtected())
        ) {
            $refMethod->setAccessible(true);
            $refMethod->invoke($object, $paramValue);
            $refMethod->setAccessible(false);
        } elseif (isset($refProperty) && $refProperty->isPublic()) {
            $refProperty->setValue($object, $paramValue);
        } elseif (isset($refProperty) && ($refProperty->isPrivate() || $refProperty->isProtected())) {
            $refProperty->setAccessible(true);
            $refProperty->setValue($object, $paramValue);
            $refProperty->setAccessible(false);
        }
    }

    /**
     * check setter
     * @return bool
     * @param \ReflectionMethod $method
     */
    protected static function checkSetter(\ReflectionMethod $method)
    {
        return count($method->getParameters()) == 1;
    }

    /**
     * @param array $loadParams
     * @return mixed
     */
    public static function runParentConstruct(array $loadParams = [])
    {
        InsideConstruct::checkContainer();

        //Who call me?;
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        $className = $trace[1]['class'];
        $reflectionClass = new \ReflectionClass($className);
        $object = $trace[1]['object'];
        /* @var $reflectionClass \ReflectionClass */

        $refParentClass = $reflectionClass->getParentClass();
        InsideConstruct::checkConstruct(($refParentConstruct = $refParentClass->getConstructor()));

        $refParams = $refParentConstruct->getParameters();

        return self::parentService($loadParams, $refParams, $object, $refParentConstruct);
    }

    /**
     * @param array $loadParams
     * @param $refParams
     * @param $object
     * @param \ReflectionMethod $refParentConstruct
     * @return mixed
     */
    protected static function parentService(array $loadParams, $refParams, $object, \ReflectionMethod $refParentConstruct)
    {
        $params = '';
        $result = [];

        /** @var \ReflectionParameter $refParam */
        foreach ($refParams as $refParam) {
            $paramName = $refParam->getName();
            if (!in_array($paramName, array_keys($loadParams))) {
                $paramValue = self::getParamValue($paramName, $refParam);
            } else {
                $paramValue = $loadParams[$paramName];
            }
            $result[$paramName] = $paramValue;
            $params .= '$result["' . $paramName . '"],';
        }
        //gen call method signature with params
        $params = trim($params, ',');
        $closure = $refParentConstruct->getClosure($object);
        eval('$closure(' . $params . ');');
        return $result;
    }

    /**
     * @param array $mapping
     * @param array $setService
     * @return array
     */
    public static function init(array $mapping = [])
    {
        $result = [];
        $loadParams = [];

        InsideConstruct::checkContainer();

        //Who call me?;
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        $className = $trace[1]['class'];
        $reflectionClass = new \ReflectionClass($className);
        /* @var $reflectionClass \ReflectionClass */
        $object = $trace[1]['object'];
        $args = $trace[1]['args'];

        //I need your __construct params
        //$reflectionClass->getMethod('__construct');
        InsideConstruct::checkConstruct(($refConstruct = $reflectionClass->getConstructor()));

        $refParams = $refConstruct->getParameters();
        // $refParams array of ReflectionParameter

        // class hasn't parent
        $refParentClass = $reflectionClass->getParentClass();
        if ($refParentClass) {
            $refParentConstruct = $refParentClass->getConstructor();
            InsideConstruct::checkConstruct($refParentConstruct);
            $refParentParams = $refParentConstruct->getParameters();
        }

        foreach ($refParams as $refParam) {
            $paramName = $refParam->getName();

            if (empty($args)) {
                //Do this param need in service loading
                //Has service in $container?
                $paramValue = self::getParamValue($paramName, $refParam);
            } else {
                //Value for param was retrived in __construct().
                $paramValue = array_shift($args);
            }
            // if class hasn't parent or service not set
            if (!$refParentClass || (!in_array($refParam, $refParentParams) && !isset($mapping[$paramName]))) {
                $result[$paramName] = $paramValue;
                InsideConstruct::setValue($reflectionClass, $paramName, $paramValue, $object);
            } else {
                if (isset($mapping[$paramName])) {
                    $loadParams[$mapping[$paramName]] = $paramValue;
                } else {
                    $loadParams[$paramName] = $paramValue;
                }
            }
        }
        //if has parent add he service
        if ($refParentClass) {
            $parentResult = self::parentService($loadParams, $refParentParams, $object, $refParentConstruct);
            $result = array_merge($result, $parentResult);
        }

        return $result;
    }

    /**
     * @param ContainerInterface $container
     */
    public static function setContainer(ContainerInterface $container)
    {
        static::$container = $container;
    }
}
