<?php
namespace STS\Core\School;

/**
 * Class SchoolDto
 * @package STS\Core\School
 */
class SchoolDto
{
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $legacyId;
    /**
     * @var
     */
    private $name;
    /**
     * @var
     */
    private $type;
    /**
     * @var bool
     */
    private $isInactive;
    /**
     * @var
     */
    private $notes;
    /**
     * @var
     */
    private $regionName;
    /**
     * @var
     */
    private $areaName;
    /**
     * @var
     */
    private $addressLineOne;
    /**
     * @var
     */
    private $addressLineTwo;
    /**
     * @var
     */
    private $addressCity;
    /**
     * @var
     */
    private $addressState;
    /**
     * @var
     */
    private $addressZip;
    /**
     * @var
     */
    private $areaId;
    /**
     * @var
     */
    private $typeKey;

    /**
     * @param string $id
     * @param $legacyId
     * @param $name
     * @param $type
     * @param bool $is_inactive
     * @param string $notes
     * @param $regionName
     * @param $areaName
     * @param $addressLineOne
     * @param $addressLineTwo
     * @param $addressCity
     * @param $addressState
     * @param $addressZip
     * @param $areaId
     * @param $typeKey
     */
    public function __construct(
        $id,
        $legacyId,
        $name,
        $type,
        $is_inactive,
        $notes,
        $regionName,
        $areaName,
        $addressLineOne,
        $addressLineTwo,
        $addressCity,
        $addressState,
        $addressZip,
        $areaId,
        $typeKey
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->legacyId = $legacyId;
        $this->type = $type;
        $this->isInactive = $is_inactive;
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

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getlegacyId()
    {
        return $this->legacyId;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return boolean
     */
    public function isInactive()
    {
        return $this->isInactive;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @return mixed
     */
    public function getAreaName()
    {
        return $this->areaName;
    }

    /**
     * @return mixed
     */
    public function getRegionName()
    {
        return $this->regionName;
    }

    /**
     * @return mixed
     */
    public function getAddressLineOne()
    {
        return $this->addressLineOne;
    }

    /**
     * @return mixed
     */
    public function getAddressLineTwo()
    {
        return $this->addressLineTwo;
    }

    /**
     * @return mixed
     */
    public function getAddressCity()
    {
        return $this->addressCity;
    }

    /**
     * @return mixed
     */
    public function getAddressState()
    {
        return $this->addressState;
    }

    /**
     * @return mixed
     */
    public function getAddressZip()
    {
        return $this->addressZip;
    }

    /**
     * @return mixed
     */
    public function getTypeKey()
    {
        return $this->typeKey;
    }

    /**
     * @return mixed
     */
    public function getAreaId()
    {
        return $this->areaId;
    }
}
