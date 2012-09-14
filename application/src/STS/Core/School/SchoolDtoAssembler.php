<?php
namespace STS\Core\School;
use STS\Domain\School;

class SchoolDTOAssembler
{
    public static function toDTO($school)
    {
        if (!($school instanceof School)) {
            throw new \InvalidArgumentException('Instance of \STS\Domain\School not provided.');
        }
        $id = $school->getId();
        $legacyId = $school->getLegacyId();
        $name = $school->getName();
        $type = $school->getType();
        $notes = $school->getNotes();
        if ($area = $school->getArea()) {
            if ($region = $area->getRegion()) {
                $regionName = $region->getName();
            } else {
                $regionName = null;
            }
            $areaName = $area->getName();
        } else {
            $regionName = null;
            $areaName = null;
        }
        if ($address = $school->getAddress()) {
            $addressLineOne = $address->getLineOne();
            $addressLineTwo = $address->getLineTwo();
            $addressCity = $address->getCity();
            $addressState = $address->getState();
            $addressZip = $address->getZip();
        } else {
            $addressLineOne = null;
            $addressLineTwo = null;
            $addressCity = null;
            $addressState = null;
            $addressZip = null;
        }
        $schoolDto = new SchoolDto($id, $legacyId, $name, $type, $notes, $regionName, $areaName, $addressLineOne,
                        $addressLineTwo, $addressCity, $addressState, $addressZip);
        return $schoolDto;
    }
}
