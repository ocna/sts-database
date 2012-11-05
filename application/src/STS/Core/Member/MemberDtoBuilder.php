<?php
namespace STS\Core\Member;

class MemberDtoBuilder
{
    private $id = null;
    private $legacyId = null;
    private $firstName = null;
    private $lastName = null;
    private $type = null;
    private $notes = null;
    private $status = null;
    private $addressLineOne = null;
    private $addressLineTwo = null;
    private $addressCity = null;
    private $addressState = null;
    private $addressZip = null;
    private $associatedUserId = null;
    private $presentsForAreas = null;
    private $facilitatesForAreas = null;
    private $coordinatesForAreas = null;
    private $coordinatesForRegions = null;
    private $email = null;
    private $dateTrained = null;
    private $diagnosisDate = null;
    private $diagnosisStage = null;
    private $phoneNumbers = null;

    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function withLegacyId($legacyId)
    {
        $this->legacyId = $legacyId;
        return $this;
    }

    public function withFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function withLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function withType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function withNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

    public function withStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function withAddressLineOne($addressLineOne)
    {
        $this->addressLineOne = $addressLineOne;
        return $this;
    }

    public function withAddressLineTwo($addressLineTwo)
    {
        $this->addressLineTwo = $addressLineTwo;
        return $this;
    }

    public function withAddressCity($addressCity)
    {
        $this->addressCity = $addressCity;
        return $this;
    }

    public function withAddressState($addressState)
    {
        $this->addressState = $addressState;
        return $this;
    }

    public function withAddressZip($addressZip)
    {
        $this->addressZip = $addressZip;
        return $this;
    }

    public function withAssociatedUserId($associatedUserId)
    {
        $this->associatedUserId = $associatedUserId;
        return $this;
    }

    public function withPresentsForAreas($presentsForAreas)
    {
        $this->presentsForAreas = $presentsForAreas;
        return $this;
    }

    public function withFacilitatesForAreas($facilitatesForAreas)
    {
        $this->facilitatesForAreas = $facilitatesForAreas;
        return $this;
    }

    public function withCoordinatesForAreas($coordinatesForAreas)
    {
        $this->coordinatesForAreas = $coordinatesForAreas;
        return $this;
    }

    public function withCoordinatesForRegions($coordinatesForRegions)
    {
        $this->coordinatesForRegions = $coordinatesForRegions;
        return $this;
    }

    public function withEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function withDateTrained($dateTrained)
    {
        $this->dateTrained = $dateTrained;
        return $this;
    }

    public function withDiagnosisDate($diagnosisDate)
    {
        $this->diagnosisDate = $diagnosisDate;
        return $this;
    }

    public function withDiagnosisStage($diagnosisStage)
    {
        $this->diagnosisStage = $diagnosisStage;
        return $this;
    }

    public function withPhoneNumbers($phoneNumbers)
    {
        $this->phoneNumbers = $phoneNumbers;
        return $this;
    }
    public function build()
    {
        return new MemberDto(
            $this->id,
            $this->legacyId,
            $this->firstName,
            $this->lastName,
            $this->type,
            $this->notes,
            $this->status,
            $this->addressLineOne,
            $this->addressLineTwo,
            $this->addressCity,
            $this->addressState,
            $this->addressZip,
            $this->associatedUserId,
            $this->presentsForAreas,
            $this->facilitatesForAreas,
            $this->coordinatesForAreas,
            $this->coordinatesForRegions,
            $this->email,
            $this->dateTrained,
            $this->diagnosisDate,
            $this->diagnosisStage,
            $this->phoneNumbers
        );
    }
}
