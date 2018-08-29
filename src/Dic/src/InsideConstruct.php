<?php


namespace rollun\dic;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use RuntimeException;

/**
 * Class InsideConstruct
 * @package rollun\dic
 */
class InsideConstruct implements InsideConstructInterface
{
    /**
     * @var ContainerInterface
     */
    private static $container = null;

    /**
     * Setup container into InsideConstructor
     * @param ContainerInterface $container
     * @return void
     */
    public static function setContainer(ContainerInterface $container)
    {
        static::$container = $container;
    }

    /**
     * Check if container is setted.
     * @throws RuntimeException
     */
    private static function validateContainer()
    {
        global $container;
        static::$container = static::$container ? static::$container : $container;
        if (!(isset(static::$container) && static::$container instanceof ContainerInterface)) {
            throw new RuntimeException(
                'global $contaner or InsideConstruct::$contaner'
                . ' must be inited'
            );
        }
    }

    /**
     * Validate InsideConstruct::class method call.
     * Can call in __wakeup and __construct
     * @param ReflectionMethod $reflectionMethod
     * @param $expectedMethodName
     */
    private static function validateCallerMethod(ReflectionMethod $reflectionMethod, $expectedMethodName)
    {
        if ($reflectionMethod->getName() !== $expectedMethodName) {
            throw new RuntimeException(
                "You must call InsideConstruct::initServices() inside $expectedMethodName only"
            );
        }
    }

    /**
     * Return info for object which call InsideConstruct
     * @see http://php.net/manual/ru/function.debug-backtrace.php
     * @return BacktraceInfo
     * @throws ReflectionException
     */
    private static function getCallerInfo()
    {
        //limit 3 because expected call from 3 nested level relative caller object
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 3);
        //todo: maybe upper limit and add search caller object in trace without "expected".
        $info = $trace[2];
        return BacktraceInfo::initFromArray($info);
    }

    /**
     * Inject dependency to caller object (Which call InsideConstruct)
     *
     * @param ReflectionClass $reflectionClass
     * @param object $object
     * @param $propertyName
     * @param $dependency
     * @return void
     */
    private static function injectDependencyToCaller(ReflectionClass $reflectionClass, $object, $propertyName, $dependency)
    {
        $setterName = "set" . ucfirst($propertyName);
        //setters
        $refMethod = $reflectionClass->hasMethod($setterName) ?
            $reflectionClass->getMethod($setterName) :
            null;
        //properties
        $refProperty = $reflectionClass->hasProperty($propertyName) ?
            $reflectionClass->getProperty($propertyName) :
            null;
        if (isset($refMethod) && static::checkSetterMethod($refMethod) && $refMethod->isPublic()) {
            $refMethod->invoke($object, $dependency);
        } elseif (
            isset($refMethod)
            && static::checkSetterMethod($refMethod)
            && ($refMethod->isPrivate() || $refMethod->isProtected())
        ) {
            $refMethod->setAccessible(true);
            $refMethod->invoke($object, $dependency);
            $refMethod->setAccessible(false);
        } elseif (isset($refProperty) && $refProperty->isPublic()) {
            $refProperty->setValue($object, $dependency);
        } elseif (isset($refProperty) && ($refProperty->isPrivate() || $refProperty->isProtected())) {
            $refProperty->setAccessible(true);
            $refProperty->setValue($object, $dependency);
            $refProperty->setAccessible(false);
        }
    }

    /**
     * Check if dependency type is simple and dosn't loaded form container
     * integer, float, string, boolean, array, resource
     * @param $type
     * @return boolean
     */
    private static function isSimpleDependency($type)
    {
        return in_array($type, [
            "int",
            "integer",
            "float",
            "string",
            "array",
            "boolean",
            "bool",
            "resource",
        ]);
    }

    /**
     * Throw error if dependency type is not equals expected
     * @param $dependency
     * @param $type
     * @throws RuntimeException
     */
    private static function validateType($dependency, $type)
    {
        if (is_null($type)) return;
        $functionName = "is_$type";
        if (function_exists($functionName)) {
            $isInvalid = $functionName($dependency);
        } else {
            $isInvalid = !is_a($dependency, $type, true);
        }
        if ($isInvalid) {
            throw new RuntimeException("Expected dependency type $type");
        }
    }

    /**
     * Type [scalar,array,resource] - not search in container
     * Type Class - search in container, throw exception if not found and if param is not nulable
     * Without typing - search in container. trigger warning if not loaded
     * @param $dependencyName
     * @param $reflectionParam
     * @throws RuntimeException
     * @return mixed
     */
    private static function getDependencyValue(ReflectionParameter $reflectionParam, $dependencyName)
    {
        $paramType = $reflectionParam->getType();

        //For version compatibility. In 7.1 __toString is deprecated, and add getName.
        if ($paramType && static::isSimpleDependency(
                method_exists($paramType, "getName") ? $paramType->getName() : $paramType->__toString()
            )) {
            //not load from container
            $dependency = $reflectionParam->getDefaultValue();
        } else {
            if (static::$container->has($dependencyName)) {
                try {
                    $dependency = static::$container->get($dependencyName);
                } catch (NotFoundExceptionInterface $e) {
                    throw new RuntimeException(
                        "Dependency with name $dependencyName not loaded cosed by exception.", $e->getCode(), $e);
                } catch (ContainerExceptionInterface $e) {
                    throw new RuntimeException(
                        "Dependency with name $dependencyName not loaded cosed by exception.", $e->getCode(), $e);
                }
                static::validateType($dependency, $paramType);
            } else {
                /*if (!$paramType) {
                    //trigger_error("Not found value for untyped param $dependencyName. Use default value", E_USER_WARNING);
                } else*/
                if ($paramType && $reflectionParam->getClass()) {
                    throw new RuntimeException("Dependency with name $dependencyName not found in container.");
                }
                $dependency = $reflectionParam->getDefaultValue();
            }
        }
        return $dependency;
    }

    /**
     * Return false if method is not setter
     *  Has mere then one params
     * @param ReflectionMethod $reflectionMethod
     * @return bool
     */
    private static function checkSetterMethod(ReflectionMethod $reflectionMethod)
    {
        //TODO: Add prototype check
        return ($reflectionMethod->getNumberOfParameters() == 1);
    }

    /**
     * Return service which expected received from method (__construct)
     * @param array $parameters
     * @param array $args
     * @param array $dependencyMapping
     * @return array
     */
    private static function getMethodDependency(array $parameters, array $args, array $dependencyMapping = [])
    {
        $dependencies = [];
        /** @var ReflectionParameter[] $parameters */
        foreach ($parameters as $reflectionParam) {
            $paramName = $reflectionParam->getName();
            if (empty($args)) {
                $dependencyName = isset($dependencyMapping[$paramName]) ? $dependencyMapping[$paramName] : $paramName;
                $dependency = static::getDependencyValue($reflectionParam, $dependencyName);
            } else {
                $dependency = array_shift($args);
            }
            $dependencies[$paramName] = $dependency;
        }
        return $dependencies;
    }

    /**
     * load service for object property if setted in dependancyMapping array
     * @param $reflectionClass
     * @param $dependencyMapping
     * @return array
     */
    private static function getPropertiesDependency(ReflectionClass $reflectionClass, array $dependencyMapping)
    {
        $dependencies = [];
        foreach ($dependencyMapping as $propertyName => $dependencyName) {
            if ($reflectionClass->hasProperty($propertyName)) {
                try {
                    $dependency = static::$container->get($dependencyName);
                    $dependencies[$propertyName] = $dependency;
                } catch (NotFoundExceptionInterface $e) {
                    throw new RuntimeException(
                        "Dependency with name $dependencyName not loaded cosed by exception.", $e->getCode(), $e);
                } catch (ContainerExceptionInterface $e) {
                    throw new RuntimeException(
                        "Dependency with name $dependencyName not loaded cosed by exception.", $e->getCode(), $e);
                }
            }
        }
        return $dependencies;
    }

    /**
     * @param $object
     * @param array $parentConstructorDependencies
     * @return array
     * @throws ReflectionException
     */
    private static function parentConstruct($object, array $parentConstructorDependencies = [])
    {
        $dependencies = [];
        $reflectionParentClass = (new ReflectionClass($object))->getParentClass();
        if (!$reflectionParentClass) {
            throw new \InvalidArgumentException("Object haven't parent __constructor.");
        }
        $reflectionParentConstruct = $reflectionParentClass->getConstructor();
        $parentParams = $reflectionParentConstruct->getParameters();
        foreach ($parentParams as $parentParam) {
            $paramName = $parentParam->getName();
            if (!array_key_exists($paramName, $parentConstructorDependencies)) {
                $dependency = static::getDependencyValue($parentParam, $paramName);
            } else {
                $dependency = $parentConstructorDependencies[$paramName];
            }
            $dependencies[$paramName] = $dependency;
        }
        $constructClosure = $reflectionParentConstruct->getClosure($object);
        $constructClosure(...array_values($dependencies));
        return $dependencies;
    }

    /**
     * Init dependency service and call parent construct with service init
     * @param array $dependencyMapping
     * @return array
     * @throws ReflectionException
     */
    public static function init(array $dependencyMapping = [])
    {
        static::validateContainer();
        $backtraceInfo = static::getCallerInfo();
        static::validateCallerMethod($backtraceInfo->getReflectionMethod(), "__construct");

        $methodDependencies = static::getMethodDependency(
            $backtraceInfo->getReflectionMethod()->getParameters(),
            $backtraceInfo->getArgs(),
            $dependencyMapping
        );

        //load parent dependency
        $parentConstructorDependencies = [];
        $reflectionParentClass = $backtraceInfo->getReflectionClass()->getParentClass();
        if ($reflectionParentClass) {
            $reflectionParentConstruct = $reflectionParentClass->getConstructor();
            $parentParams = $reflectionParentConstruct->getParameters();
            foreach ($parentParams as $parentParam) {
                if (in_array($parentParam->getName(), $dependencyMapping)) {
                    $dependencyName = array_search($parentParam->getName(), $dependencyMapping);
                    $dependency = $methodDependencies[$dependencyName];
                    $parentConstructorDependencies[$parentParam->getName()] = $dependency;
                }
            }
        }

        $dependencyMapping = array_diff_key($dependencyMapping, $methodDependencies);

        $propertiesDependency = static::getPropertiesDependency(
            $backtraceInfo->getReflectionClass(), $dependencyMapping);
        $dependencies = array_merge($methodDependencies, $propertiesDependency);

        //inject dependency
        foreach ($dependencies as $propertyName => $dependency) {
            static::injectDependencyToCaller($backtraceInfo->getReflectionClass(), $backtraceInfo->getObject(), $propertyName, $dependency);
        }
        if ($reflectionParentClass) {
            static::parentConstruct($backtraceInfo->getObject(), $parentConstructorDependencies);
        }
        return $dependencies;
    }

    /**
     * Init service in __wakeup method
     * @param array $dependencyMapping
     * @return array
     * @throws ReflectionException
     */
    public static function initWakeup(array $dependencyMapping = [])
    {
        static::validateContainer();
        $backtraceInfo = static::getCallerInfo();
        static::validateCallerMethod($backtraceInfo->getReflectionMethod(), "__wakeup");

        $methodDependencies = static::getMethodDependency(
            $backtraceInfo->getReflectionMethod()->getParameters(),
            $backtraceInfo->getArgs(),
            $dependencyMapping
        );
        $propertiesDependency = static::getPropertiesDependency(
            $backtraceInfo->getReflectionClass(),
            array_diff_key($dependencyMapping, $methodDependencies)
        );
        $dependencies = array_merge($methodDependencies, $propertiesDependency);
        //inject dependency
        foreach ($dependencies as $propertyName => $dependency) {
            static::injectDependencyToCaller($backtraceInfo->getReflectionClass(), $backtraceInfo->getObject(), $propertyName, $dependency);
        }
        return $dependencies;
    }

    /**
     * Init service usage dependency service from __constructor args
     * @param array $dependencyMapping
     * @return array
     * @throws ReflectionException
     */
    public static function setConstructParams(array $dependencyMapping = [])
    {
        static::validateContainer();
        $backtraceInfo = static::getCallerInfo();
        static::validateCallerMethod($backtraceInfo->getReflectionMethod(), "__construct");

        $methodDependencies = static::getMethodDependency(
            $backtraceInfo->getReflectionMethod()->getParameters(),
            $backtraceInfo->getArgs(),
            $dependencyMapping
        );
        $propertiesDependency = static::getPropertiesDependency(
            $backtraceInfo->getReflectionClass(),
            array_diff_key($dependencyMapping, $methodDependencies)
        );
        $dependencies = array_merge($methodDependencies, $propertiesDependency);
        //inject dependency
        foreach ($dependencies as $propertyName => $dependency) {
            static::injectDependencyToCaller($backtraceInfo->getReflectionClass(), $backtraceInfo->getObject(), $propertyName, $dependency);
        }
        return $dependencies;
    }

    /**
     * Run parent constructor (parent::__constructor(...))
     * with init service usage dependency service from __constructor args
     * @param array $loadParams
     * @return array
     * @throws ReflectionException
     */
    public static function runParentConstruct(array $loadParams = [])
    {
        static::validateContainer();
        $backtraceInfo = static::getCallerInfo();
        static::validateCallerMethod($backtraceInfo->getReflectionMethod(), "__construct");

        return static::parentConstruct($backtraceInfo->getObject(), $loadParams);
    }
}