<?php
Namespace STS\Core\Member;

class MemberDto
{

    private $id;
    private $legacyId;
    private $firstName;
    public function __construct($id, $legacyId, $firstName, $lastName)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->legacyId = $legacyId;
    }
    public function getFirstName()
    {
        return $this->firstName;
    }
    public function getlegacyId()
    {
        return $this->legacyId;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getLastName()
    {
        return $this->lastName;
    }
}
