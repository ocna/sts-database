<?php
namespace STS\Core\Location;

use STS\Domain\Location\Area;

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

    public static function assembleFromArea(Area $area)
    {
        $region = $area->getRegion();
        $class = __CLASS__;
        $dto = new $class(
            $area->getId(),
            $area->getName(),
            $area->getLegacyId(),
            $area->getCity(),
            $area->getState(),
            $region->getName()
        );

        return $dto;
    }
}
