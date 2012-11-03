<?php
namespace STS\Domain\Member;

class PhoneNumber
{
    const TYPE_HOME = 'home';
    const TYPE_CELL = 'cell';
    const TYPE_WORK = 'work';

    private $type;
    private $number;

     /**
      * @param string $number
      * @param string $type
      */
    public function __construct($number, $type)
    {
        $this->number = $number;
        $this->setType($type);
    }

    public function getNumber()
    {
        return $this->number;
    }

    public static function getAvailableTypes()
    {
        $reflected = new \ReflectionClass(get_called_class());
        $types = array();
        foreach ($reflected->getConstants() as $key => $value) {
            if (substr($key, 0, 5) == 'TYPE_') {
                $types[$key] = $value;
            }
        }
        return $types;
    }
    public static function getAvailableType($key)
    {
        if (substr($key, 0, 5) != 'TYPE_') {
            throw new \InvalidArgumentException('Type key must begin with "TYPE_".');
        }
        if (!array_key_exists($key, static::getAvailableTypes())) {
            throw new \InvalidArgumentException('No such type with given key.');
        }
        $reflected = new \ReflectionClass(get_called_class());
        return $reflected->getConstant($key);
    }
    public function setType($type)
    {
        if ($type !== null && !in_array($type, static::getAvailableTypes(), true)) {
            throw new \InvalidArgumentException('No such type with given value.');
        }
        $this->type = $type;
        return $this;
    }
    public function getType()
    {
        return $this->type;
    }
}
