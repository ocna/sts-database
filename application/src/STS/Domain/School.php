<?php
namespace STS\Domain;

use STS\Domain\Location\Address;
use STS\Domain\Location\Area;
use STS\Domain\Location\Locatable;

class School extends EntityWithTypes implements Locatable
{
    const TYPE_NP = 'NP';
    const TYPE_PA = 'PA';
    const TYPE_NURSING = 'Nursing';
    const TYPE_MEDICAL = 'Medical';
    const TYPE_OTHER = 'Other';

    private $legacyId;
    private $name;
    /**
     * @var Area
     */
    private $area;
    /**
     * @var Address
     */
    private $address;
    private $notes;

    /**
     * @var bool
     */
    private $isInactive = false;

    public function toMongoArray()
    {
        $areaId = new \MongoId($this->area->getId());
        $array = array(
            'id' => $this->id,
            'name' => utf8_encode($this->name),
            'type' => $this->type, 'notes' => utf8_encode($this->notes),
            'legacyid' => $this->legacyId,
            'is_inactive'   => $this->isInactive,
            'area_id' => array(
                '_id' => $areaId
            ),
            'address' => utf8_encode($this->address->getAddress()),
            'dateCreated' => new \MongoDate($this->getCreatedOn()),
            'dateUpdated' => new \MongoDate($this->getUpdatedOn())
        );
        return $array;
    }

    /**
     * @return mixed
     */
    public function isInactive()
    {
        return $this->isInactive;
    }

    /**
     * @param bool $isInactive
     * @return $this
     */
    public function setIsInactive($isInactive)
    {
        if ($isInactive) {
            $this->isInactive = true;
        } else {
            $this->isInactive = false;
        }
        return $this;
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

    /**
     * @return \STS\Domain\Location\Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return \STS\Domain\Location\Area
     */
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
        $this->name = trim(preg_replace('/\s+/', ' ', $name));
        return $this;
    }
}
