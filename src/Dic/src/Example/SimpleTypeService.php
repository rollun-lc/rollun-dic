<?php


namespace rollun\dic\Example;


class SimpleTypeService
{
    private $int;
    private $string;
    private $float;
    private $array;

    /**
     * SimpleTypeService constructor.
     * @param int $int
     * @param string $string
     * @param float $float
     * @param array $array
     */
    public function __construct(int $int, string $string, float $float = 9.8, array $array = [])
    {
        $this->int = $int;
        $this->string = $string;
        $this->float = $float;
        $this->array = $array;
    }

    /**
     * @return int
     */
    public function getInt()
    {
        return $this->int;
    }

    /**
     * @return string
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @return float
     */
    public function getFloat()
    {
        return $this->float;
    }

    /**
     * @return array
     */
    public function getArray()
    {
        return $this->array;
    }


}