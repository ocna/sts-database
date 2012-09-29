<?php
namespace STS\Core\Member;
use STS\Domain\Member;

class MemberDtoAssembler
{
    public static function toDTO($member)
    {
        if (!($member instanceof Member)) {
            throw new \InvalidArgumentException('Instance of \STS\Domain\Member not provided.');
        }
        $id = $member->getId();
        $legacyId = $member->getLegacyId();
        $firstName = $member->getFirstName();
        $lastName = $member->getLastName();
        $deceased = $member->isDeceased();
        $type = $member->getType();
        $notes = $member->getNotes();
        $associatedUserId = $member->getAssociatedUserId();
        if ($address = $member->getAddress()) {
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
        $presentsForAreas = self::getAreaNamesArray($member->getPresentsForAreas());
        $facilitatesForAreas = self::getAreaNamesArray($member->getFacilitatesForAreas());
        $coordinatesForAreas = self::getAreaNamesArray($member->getCoordinatesForAreas());
        $coordinatesForRegions = self::getRegionNamesForAreas($member->getCoordinatesForAreas());
        return new MemberDto($id, $legacyId, $firstName, $lastName, $type, $notes, $deceased, $addressLineOne,
                        $addressLineTwo, $addressCity, $addressState, $addressZip, $associatedUserId,
                        $presentsForAreas, $facilitatesForAreas, $coordinatesForAreas, $coordinatesForRegions);
    }
    private static function getAreaNamesArray($areas)
    {
        $areaArray = array();
        foreach ($areas as $area) {
            $areaArray[$area->getId()] = $area->getName();
        }
        return $areaArray;
    }
    private static function getRegionNamesForAreas($areas)
    {
        $regionArray = array();
        foreach ($areas as $area) {
            if ($region = $area->getRegion()) {
                if (!in_array($region->getName(), $regionArray)) {
                    $regionArray[] = $region->getName();
                }
            }
        }
        return $regionArray;
    }
}
