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
        $builder = new MemberDtoBuilder();
        $builder->withId($member->getId())
                ->withLegacyId($member->getLegacyId())
                ->withFirstName($member->getFirstName())
                ->withLastName($member->getLastName())
                ->withStatus($member->getStatus())
                ->withType($member->getType())
                ->withNotes($member->getNotes())
                ->withAssociatedUserId($member->getAssociatedUserId());
        if ($address = $member->getAddress()) {
            $builder->withAddressLineOne($address->getLineOne())
                    ->withAddressLineTwo($address->getLineTwo())
                    ->withAddressCity($address->getCity())
                    ->withAddressState($address->getState())
                    ->withAddressZip($address->getZip());
        }
        $builder->withPresentsForAreas(self::getAreaNamesArray($member->getPresentsForAreas()))
                ->withFacilitatesForAreas(self::getAreaNamesArray($member->getFacilitatesForAreas()))
                ->withCoordinatesForAreas(self::getAreaNamesArray($member->getCoordinatesForAreas()))
                ->withCoordinatesForRegions(self::getRegionNamesForAreas($member->getCoordinatesForAreas()))
                ->withEmail($member->getEmail())
                ->withDateTrained($member->getDateTrained());
        if ($diagnosis = $member->getDiagnosis()) {
                $builder->withDiagnosisDate($diagnosis->getDate())
                        ->withDiagnosisStage($diagnosis->getStage());
        }
        if ($phoneNumbers = $member->getPhoneNumbers()) {
            $builder->withPhoneNumbers(self::getPhoneNumbersArray($phoneNumbers));
        }
        return $builder->build();
    }
    private static function getPhoneNumbersArray($phoneNumbers)
    {
        $phoneNumbersArray = array();
        foreach ($phoneNumbers as $phoneNumber) {
            $phoneNumbersArray[] = array('number'=>$phoneNumber->getNumber(), 'type'=>$phoneNumber->getType());
        }
        return $phoneNumbersArray;
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
