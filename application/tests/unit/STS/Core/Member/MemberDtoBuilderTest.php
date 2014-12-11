<?php
namespace STS\Core\Member;

use STS\Core\Member\MemberDtoBuilder;
use STS\TestUtilities\MemberTestCase;

class MemberDtoBuilderTest extends MemberTestCase
{
    /**
     * @test
     */
    public function validBuiltDto()
    {
        $member = $this->getValidMember();
        $builder = new MemberDtoBuilder();
        $builder->withId($member->getId())
                ->withLegacyId($member->getLegacyId())
                ->withFirstName($member->getFirstName())
                ->withLastName($member->getLastName())
                ->withStatus($member->getStatus())
                ->withVolunteer($member->isVolunteer())
                ->withType($member->getType())
                ->withNotes($member->getNotes())
                ->withAssociatedUserId($member->getAssociatedUserId())
                ->withAddressLineOne($member->getAddress()->getLineOne())
                ->withAddressLineTwo($member->getAddress()->getLineTwo())
                ->withAddressCity($member->getAddress()->getCity())
                ->withAddressState($member->getAddress()->getState())
                ->withAddressZip($member->getAddress()->getZip())
                ->withPresentsForAreas($this->getValidPresentsForAreasArray())
                ->withFacilitatesForAreas($this->getValidFacilitatesForAreasArray())
                ->withCoordinatesForAreas($this->getValidCoordinatesForAreasArray())
                ->withCoordinatesForRegions($this->getValidCoordinatesForRegionsArray())
                ->withEmail($member->getEmail())
                ->withDateTrained($member->getDateTrained())
                ->withDiagnosisDate($member->getDiagnosis()->getDate())
                ->withDiagnosisStage($member->getDiagnosis()->getStage())
                ->withPhoneNumbers($this->getValidPhoneNumbersArray())
                ->withCanBeDeleted($member->canBeDeleted());
        $dto = $builder->build();
        $this->assertValidMemberDto($dto);
    }
}
