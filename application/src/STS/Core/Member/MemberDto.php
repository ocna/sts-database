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
    private $deceased = false;
    private $addressLineOne;
    private $addressLineTwo;
    private $addressCity;
    private $addressState;
    private $addressZip;
    private $associatedUserId;
    public function getAssociatedUserId()
    {
        return $this->associatedUserId;
    }
    public function __construct($id, $legacyId, $firstName, $lastName, $type, $notes, $deceased, $addressLineOne,
                    $addressLineTwo, $addressCity, $addressState, $addressZip, $associatedUserId)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->legacyId = $legacyId;
        $this->type = $type;
        $this->notes = $notes;
        $this->deceased = $deceased;
        $this->addressLineOne = $addressLineOne;
        $this->addressLineTwo = $addressLineTwo;
        $this->addressCity = $addressCity;
        $this->addressState = $addressState;
        $this->addressZip = $addressZip;
        $this->associatedUserId = $associatedUserId;
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
        return $this->deceased;
    }
}
