<?php
namespace STS\Domain;
use STS\Domain\Entity;

class School extends Entity
{
    const TYPE_SCHOOL = 'School';
    const TYPE_HOSPITAL = 'Hospital';

    private $legacyId;
    private $name;
    private $area;
    private $type;
    private $address;
    private $notes;
    public function getNotes()
    {
        return $this->notes;
    }
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }
    public function getAddress()
    {
        return $this->address;
    }
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }
    public function getType()
    {
        return $this->type;
    }
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
    public function getArea()
    {
        return $this->area;
    }
    public function setArea($area)
    {
        $this->area = $area;
        return $this;
    }
    public function getLegacyId()
    {
        return $this->legacyId;
    }
    public function setLegacyId($legacyId)
    {
        $this->legacyId = $legacyId;
        return $this;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    public static function getTypes()
    {
        $reflected = new \ReflectionClass('STS\Domain\School');
        $constants = $reflected->getConstants();
        $types = array();
        foreach ($constants as $key => $value) {
            if (substr($key, 0, 5) == 'TYPE_') {
                $types[] = $value;
            }
        }
        return $types;
    }
}
