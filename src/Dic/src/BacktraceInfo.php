<?php


namespace rollun\dic;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

/**
 * Class BacktraceInfo
 * @package rollun\dic
 */
class BacktraceInfo
{

    /** @var string */
    private $class;
    /** @var object */
    private $object;
    /** @var string */
    private $function;
    /** @var string */
    private $type = null;
    /** @var array */
    private $args = [];
    /** @var ReflectionClass */
    private $reflectionClass = null;
    /** @var ReflectionMethod */
    private $reflectionMethod = null;

    /**
     * @param array $traceInfo
     * @return static
     * @throws ReflectionException
     */
    public static function initFromArray(array $traceInfo)
    {
        $info = new static();
        foreach ($traceInfo as $key => $value) {
            if (property_exists(static::class, $key)) {
                $info->$key = $value;
            }
        }
        $info->reflectionClass = new ReflectionClass($info->class);
        if ($info->type) {
            $info->reflectionMethod = $info->reflectionClass->getMethod($info->function);
        }
        return $info;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     * @return BacktraceInfo
     */
    public function setClass(string $class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param object $object
     * @return BacktraceInfo
     */
    public function setObject(object $object)
    {
        $this->object = $object;
        return $this;
    }

    /**
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * @param string $function
     * @return BacktraceInfo
     */
    public function setFunction(string $function)
    {
        $this->function = $function;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return BacktraceInfo
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @param array $args
     * @return BacktraceInfo
     */
    public function setArgs(array $args)
    {
        $this->args = $args;
        return $this;
    }

    /**
     * @return ReflectionClass
     */
    public function getReflectionClass()
    {
        return $this->reflectionClass;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return BacktraceInfo
     */
    public function setReflectionClass(ReflectionClass $reflectionClass)
    {
        $this->reflectionClass = $reflectionClass;
        return $this;
    }

    /**
     * @return ReflectionMethod
     */
    public function getReflectionMethod()
    {
        return $this->reflectionMethod;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return BacktraceInfo
     */
    public function setReflectionMethod(ReflectionMethod $reflectionMethod)
    {
        $this->reflectionMethod = $reflectionMethod;
        return $this;
    }

}