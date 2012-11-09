<?php
namespace STS\Core\School;

class SchoolDto
{

    private $id;
    private $legacyId;
    private $name;
    private $type;
    private $notes;
    private $regionName;
    private $areaName;
    private $addressLineOne;
    private $addressLineTwo;
    private $addressCity;
    private $addressState;
    private $addressZip;
    private $areaId;
    private $typeKey;
    public function __construct($id, $legacyId, $name, $type, $notes, $regionName, $areaName, $addressLineOne, $addressLineTwo, $addressCity, $addressState, $addressZip, $areaId, $typeKey)
    {
        $this->id = $id;
        $this->name = $name;
        $this->legacyId = $legacyId;
        $this->type = $type;
        $this->notes = $notes;
        $this->regionName = $regionName;
        $this->areaName = $areaName;
        $this->addressLineOne = $addressLineOne;
        $this->addressLineTwo = $addressLineTwo;
        $this->addressCity = $addressCity;
        $this->addressState = $addressState;
        $this->addressZip = $addressZip;
        $this->areaId = $areaId;
        $this->typeKey = $typeKey;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getlegacyId()
    {
        return $this->legacyId;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getType()
    {
        return $this->type;
    }
    public function getNotes()
    {
        return $this->notes;
    }
    public function getAreaName()
    {
        return $this->areaName;
    }
    public function getRegionName()
    {
        return $this->regionName;
    }
    public function getAddressLineOne()
    {
        return $this->addressLineOne;
    }
    public function getAddressLineTwo()
    {
        return $this->addressLineTwo;
    }
    public function getAddressCity()
    {
        return $this->addressCity;
    }
    public function getAddressState()
    {
        return $this->addressState;
    }
    public function getAddressZip()
    {
        return $this->addressZip;
    }
    public function getTypeKey()
    {
        return $this->typeKey;
    }
    public function getAreaId()
    {
        return $this->areaId;
    }
}
