<?php
namespace STS\Core\School;

use STS\Domain\School;

class SchoolDtoAssembler
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
        $typeKey = array_search($type, School::getAvailableTypes());
        $is_inactive = $school->isInactive();
        $notes = $school->getNotes();
        if ($area = $school->getArea()) {
            if ($region = $area->getRegion()) {
                $regionName = $region->getName();
            } else {
                $regionName = null;
            }
            $areaName = $area->getName();
            $areaId = $area->getId();
        } else {
            $regionName = null;
            $areaName = null;
        }
        $address = null;
        if ($school->getAddress()) {
            $address = $school->getAddress()->getAddress();
        }
        $schoolDto = new SchoolDto(
            $id,
            $legacyId,
            $name,
            $type,
            $is_inactive,
            $notes,
            $regionName,
            $areaName,
            $address,
            $areaId,
            $typeKey
        );
        return $schoolDto;
    }
}
