<?php
namespace STS\Domain\Location;

class Area
{

    private $id;
    private $name;
    private $legacyId;
    private $city;
    private $state;
    private $region;
    public function getRegion()
    {
        return $this->region;
    }
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }
    public function getState()
    {
        return $this->state;
    }
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }
    public function getCity()
    {
        return $this->city;
    }
    public function setCity($city)
    {
        $this->city = $city;
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
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}
