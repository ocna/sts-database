<?php
namespace STS\Core\Member;

use STS\Domain\Member;
use STS\Domain\Location\Area;
use STS\Domain\Member\PhoneNumber;

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
                ->withVolunteer($member->isVolunteer())
                ->withType($member->getType())
                ->withNotes($member->getNotes())
                ->withDateTrained($member->getDateTrained())
                ->withAssociatedUserId($member->getAssociatedUserId())
                ->withCanBeDeleted($member->canBeDeleted());
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

        if ($activities = $member->getActivities()) {
            $builder->withActivities($activities);
        }

        if ($phoneNumbers = $member->getPhoneNumbers()) {
            $builder->withPhoneNumbers(self::getPhoneNumbersArray($phoneNumbers));
        }
        return $builder->build();
    }

    private static function getPhoneNumbersArray($phoneNumbers)
    {
        $phoneNumbersArray = array();
        /** @var PhoneNumber $phoneNumber */
        foreach ($phoneNumbers as $phoneNumber) {
            $phoneNumbersArray[$phoneNumber->getType()] = array(
                'number'    => $phoneNumber->getNumber(),
                'type'      => $phoneNumber->getType()
            );
        }
        return $phoneNumbersArray;
    }

    private static function getAreaNamesArray($areas)
    {
        $areaArray = array();
        /** @var Area $area */
        foreach ($areas as $area) {
            $areaArray[$area->getId()] = $area->getName();
        }
        return $areaArray;
    }

    private static function getRegionNamesForAreas($areas)
    {
        $regionArray = array();
        /** @var Area $area */
        foreach ($areas as $area) {
            if ($region = $area->getRegion()) {
                if (!in_array($region->getName(), $regionArray)) {
                    $regionArray[$region->getName()] = $region->getName();
                }
            }
        }
        return $regionArray;
    }
}
