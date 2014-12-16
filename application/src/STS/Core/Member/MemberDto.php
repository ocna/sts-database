<?php

namespace STS\Core\Member;

class MemberDto
{
    private $id;
    private $legacyId;
    private $firstName;
    private $lastName;
    private $displayName;
    private $type;
    private $activities;
    private $notes;
    private $address;
    private $associatedUserId;
    private $presentsForAreas;
    private $facilitatesForAreas;
    private $coordinatesForAreas;
    private $coordinatesForRegions;
    private $status;
    /**
     * @var bool
     */
    private $isVolunteer;
    private $email;
    private $dateTrained;
    private $diagnosisDate;
    private $diagnosisStage;
    private $phoneNumbers;
    private $canBeDeleted;

    public function __construct(
        $id,
        $legacyId,
        $firstName,
        $lastName,
        $type,
        $notes,
        $status,
        $is_volunteer,
        $activities,
        $address,
        $associatedUserId,
        $presentsForAreas,
        $facilitatesForAreas,
        $coordinatesForAreas,
        $coordinatesForRegions,
        $email,
        $dateTrained,
        $diagnosisDate,
        $diagnosisStage,
        $phoneNumbers,
        $canBeDeleted
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->displayName = $this->firstName . ' ' . $this->lastName;
        $this->legacyId = $legacyId;
        $this->type = $type;
        $this->notes = $notes;
        $this->status = $status;
        $this->isVolunteer = $is_volunteer;
        $this->activities = $activities;
        $this->address = $address;
        $this->associatedUserId = $associatedUserId;
        $this->presentsForAreas = $presentsForAreas;
        $this->facilitatesForAreas = $facilitatesForAreas;
        $this->coordinatesForAreas = $coordinatesForAreas;
        $this->coordinatesForRegions = $coordinatesForRegions;
        $this->email = $email;
        $this->dateTrained = $dateTrained;
        $this->diagnosisDate = $diagnosisDate;
        $this->diagnosisStage = $diagnosisStage;
        $this->phoneNumbers = $phoneNumbers;
        $this->canBeDeleted = $canBeDeleted;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getDateTrained()
    {
        return ! is_null($this->dateTrained) ? date('m/d/Y', strtotime($this->dateTrained)) : null;
    }

    public function getDiagnosisDate()
    {
        return ! is_null($this->diagnosisDate) ? date('m/d/Y', strtotime($this->diagnosisDate)) : null;
    }

    public function getDiagnosisStage()
    {
        return $this->diagnosisStage;
    }

    public function getPhoneNumbers()
    {
        return $this->phoneNumbers;
    }

    public function getCoordinatesForRegions()
    {
        return $this->coordinatesForRegions;
    }

    public function getCoordinatesForAreas()
    {
        return $this->coordinatesForAreas;
    }
    public function getFacilitatesForAreas()
    {
        return $this->facilitatesForAreas;
    }

    public function getPresentsForAreas()
    {
        return $this->presentsForAreas;
    }

    public function getAssociatedUserId()
    {
        return $this->associatedUserId;
    }

    public function getlegacyId()
    {
        return $this->legacyId;
    }
    public function getId()
    {
        return $this->id;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function isDeceased()
    {
        return $this->status == 'Deceased';
    }

    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isVolunteer()
    {
        return $this->isVolunteer;
    }

    public function canBeDeleted()
    {
        return $this->canBeDeleted;
    }

    public function getActivities()
    {
        return $this->activities;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }
}
