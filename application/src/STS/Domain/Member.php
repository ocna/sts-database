<?php
namespace STS\Domain;
use STS\Domain\EntityWithTypes;

class Member extends EntityWithTypes
{
    const TYPE_CAREGIVER = 'Caregiver';
    const TYPE_FAMILY_MEMBER = 'Family Member';
    const TYPE_SURVIVOR = 'Survivor';

    const STATUS_ACTIVE = 'Active';
    const STATUS_INACTIVE = 'Inactive';
    const STATUS_DECEASED = 'Deceased';

    private $legacyId;
    private $firstName;
    private $lastName;
    private $presentsFor = array();
    private $facilitatesFor = array();
    private $coordinatesFor = array();
    private $notes;
    private $deceased = false;
    private $address;
    private $associatedUserId = null;
    private $status;

    public function getStatus(){
        return $this->status;
    }

    public static function getAvailableStatuses()
    {
        $reflected = new \ReflectionClass(get_called_class());
        $statuses = array();
        foreach ($reflected->getConstants() as $key => $value) {
            if (substr($key, 0, 7) == 'STATUS_') {
                $statuses[$key] = $value;
            }
        }
        return $statuses;
    }
    public static function getAvailableStatus($key)
    {
        if (substr($key, 0, 7) != 'STATUS_') {
            throw new \InvalidArgumentException('Type key must begin with "STATUS_".');
        }
        if (!array_key_exists($key, static::getAvailableTypes())) {
            throw new \InvalidArgumentException('No such status with given key.');
        }
        $reflected = new \ReflectionClass(get_called_class());
        return $reflected->getConstant($key);
    }
    public function setStatus($status)
    {
        if ($status !== null && !in_array($status, static::getAvailableStatuses(), true)) {
            throw new \InvalidArgumentException('No such status with given value.');
        }
        $this->status = $status;
        return $this;
    }

    public function getAssociatedUserId()
    {
        return $this->associatedUserId;
    }
    public function setAssociatedUserId($associatedUserId)
    {
        $this->associatedUserId = $associatedUserId;
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
    public function hasPassedAway()
    {
        $this->setStatus(self::STATUS_DECEASED);
        return $this;
    }
    public function isDeceased()
    {
        if($this->getStatus()==self::STATUS_DECEASED){
            return true;
        }else{
            return false;
        }
    }
    public function getNotes()
    {
        return $this->notes;
    }
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }
    public function getFirstName()
    {
        return $this->firstName;
    }
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }
    public function getLastName()
    {
        return $this->lastName;
    }
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
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
    public function canPresentForArea($area)
    {
        $this->presentsFor[] = $area;
        return $this;
    }
    public function getPresentsForAreas()
    {
        return $this->presentsFor;
    }
    public function canFacilitateForArea($area)
    {
        $this->facilitatesFor[] = $area;
        return $this;
    }
    public function getFacilitatesForAreas()
    {
        return $this->facilitatesFor;
    }
    public function canCoordinateForArea($area)
    {
        $this->coordinatesFor[] = $area;
        return $this;
    }
    public function getCoordinatesForAreas()
    {
        return $this->coordinatesFor;
    }
    public function getAllAssociatedAreas()
    {
        $areas = array();
        $this->addUniqueElements($areas, $this->presentsFor);
        $this->addUniqueElements($areas, $this->facilitatesFor);
        $this->addUniqueElements($areas, $this->coordinatesFor);
        return $areas;
    }
    private function addUniqueElements(&$array, $elements)
    {
        foreach ($elements as $element) {
            if (!in_array($element, $array)) {
                $array[] = $element;
            }
        }
    }
}
