<?php
namespace STS\Core\Location;

class AreaDto
{

    private $id;
    private $name;
    private $legacyId;
    private $city;
    private $state;
    private $regionName;
    public function __construct($id, $name, $legacyId, $city, $state, $regionName)
    {
        $this->id = $id;
        $this->name = $name;
        $this->legacyId = $legacyId;
        $this->city = $city;
        $this->state = $state;
        $this->regionName = $regionName;
    }
    public function getRegionName()
    {
        return $this->regionName;
    }
    public function getState()
    {
        return $this->state;
    }
    public function getCity()
    {
        return $this->city;
    }
    public function getLegacyId()
    {
        return $this->legacyId;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getId()
    {
        return $this->id;
    }
}
