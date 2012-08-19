<?php
namespace STS\Domain;
use STS\Domain\Entity;

class Member extends Entity
{

    private $legacyId;
    private $firstName;
    private $lastName;
    private $presentsFor = array();
    private $facilitatesFor = array();
    private $coordinatesFor = array();
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
    
    private function addUniqueElements(&$array, $elements){
        foreach ($elements as $element){
            if (!in_array($element, $array)){
                $array[]= $element;
            }
        }
    }
}
