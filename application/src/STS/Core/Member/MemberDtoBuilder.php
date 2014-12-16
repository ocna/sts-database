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
    private $activities = null;
    private $status = null;
    private $isVolunteer = null;
    private $address = null;
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
    private $canBeDeleted = null;

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

    public function withVolunteer($is_volunteer)
    {
        $this->isVolunteer = $is_volunteer;
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

    public function withAddress($address)
    {
        $this->address = $address;
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

    public function withActivities($activities)
    {
        $this->activities = $activities;
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

    public function withCanBeDeleted($canBeDeleted)
    {
        $this->canBeDeleted = $canBeDeleted;
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
            $this->isVolunteer,
            $this->activities,
            $this->address,
            $this->associatedUserId,
            $this->presentsForAreas,
            $this->facilitatesForAreas,
            $this->coordinatesForAreas,
            $this->coordinatesForRegions,
            $this->email,
            $this->dateTrained,
            $this->diagnosisDate,
            $this->diagnosisStage,
            $this->phoneNumbers,
            $this->canBeDeleted
        );
    }
}
