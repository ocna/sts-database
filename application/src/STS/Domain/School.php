<?php
namespace STS\Domain;
use STS\Domain\Entity;

class School extends Entity
{

    private $legacyId;
    private $name;
    private $area;
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
}
