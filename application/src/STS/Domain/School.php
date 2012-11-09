<?php
namespace STS\Domain;
use STS\Domain\EntityWithTypes;

class School extends EntityWithTypes
{
    const TYPE_SCHOOL = 'School';
    const TYPE_HOSPITAL = 'Hospital';
    const TYPE_NP = 'NP';
    const TYPE_PA = 'PA';

    private $legacyId;
    private $name;
    private $area;
    private $address;
    private $notes;
    public function toMongoArray()
    {
        $areaId = new \MongoId($this->area->getId());
        $array = array(
                'id' => $this->id, 'name' => $this->name, 'type' => $this->type, 'notes' => $this->notes,
                'legacyid' => $this->legacyId,
                'area_id' => array(
                    '_id' => $areaId
                ),
                'address' => array(
                        'line_one' => $this->address->getLineOne(), 'line_two' => $this->address->getLineTwo(),
                        'city' => $this->address->getCity(), 'state' => $this->address->getState(),
                        'zip' => $this->address->getZip()
                ),
                'dateCreated' => new \MongoDate($this->getCreatedOn()),
                'dateUpdated' => new \MongoDate($this->getUpdatedOn())
        );
        return $array;
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
    public function getAddress()
    {
        return $this->address;
    }
    public function setAddress($address)
    {
        $this->address = $address;
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
        $this->name = trim(preg_replace('/\s+/', ' ', $name));
        return $this;
    }
}
