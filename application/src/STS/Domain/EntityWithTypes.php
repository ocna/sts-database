<?php
namespace STS\Domain;

class EntityWithTypes extends Entity
{

    protected $type;
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
