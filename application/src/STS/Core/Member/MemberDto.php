<?php
Namespace STS\Core\Member;

class MemberDto
{
    private $id;
    private $legacyId;
    private $firstName;
    private $lastName;
    private $type;
    private $notes;
    private $addressLineOne;
    private $addressLineTwo;
    private $addressCity;
    private $addressState;
    private $addressZip;
    private $associatedUserId;
    private $presentsForAreas;
    private $facilitatesForAreas;
    private $coordinatesForAreas;
    private $coordinatesForRegions;
    private $status;
    private $email;
    private $dateTrained;
    private $diagnosisDate;
    private $diagnosisStage;
    private $phoneNumbers;

    public function __construct(
        $id,
        $legacyId,
        $firstName,
        $lastName,
        $type,
        $notes,
        $status,
        $addressLineOne,
        $addressLineTwo,
        $addressCity,
        $addressState,
        $addressZip,
        $associatedUserId,
        $presentsForAreas,
        $facilitatesForAreas,
        $coordinatesForAreas,
        $coordinatesForRegions,
        $email,
        $dateTrained,
        $diagnosisDate,
        $diagnosisStage,
        $phoneNumbers
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->legacyId = $legacyId;
        $this->type = $type;
        $this->notes = $notes;
        $this->status = $status;
        $this->addressLineOne = $addressLineOne;
        $this->addressLineTwo = $addressLineTwo;
        $this->addressCity = $addressCity;
        $this->addressState = $addressState;
        $this->addressZip = $addressZip;
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
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getDateTrained()
    {
        return $this->dateTrained ? date('n/j/Y', strtotime($this->dateTrained)) : null;
    }

    public function getDiagnosisDate()
    {
        return $this->diagnosisDate ? date('n/j/Y', strtotime($this->diagnosisDate)) : null;
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
    public function getAddressLineOne()
    {
        return $this->addressLineOne;
    }
    public function getAddressLineTwo()
    {
        return $this->addressLineTwo;
    }
    public function getAddressCity()
    {
        return $this->addressCity;
    }
    public function getAddressState()
    {
        return $this->addressState;
    }
    public function getAddressZip()
    {
        return $this->addressZip;
    }
    public function isDeceased()
    {
        return $this->status == 'Deceased';
    }
    public function getStatus()
    {
        return $this->status;
    }
}
