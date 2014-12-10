<?php
namespace STS\Core\User;

class UserDTO
{
    private $id;
    private $email;
    private $firstName;
    private $lastName;
    private $role;
    private $legacyId;
    private $associatedMemberId;
    private $salt;
    private $password;

    public function __construct(
        $id,
        $email,
        $firstName,
        $lastName,
        $role,
        $legacyId,
        $associatedMemberId,
        $pw,
        $salt
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->role = $role;
        $this->legacyId = $legacyId;
        $this->associatedMemberId = $associatedMemberId;
        $this->password = $pw;
        $this->salt = $salt;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getFirstName()
    {
        return $this->firstName;
    }
    public function getLastName()
    {
        return $this->lastName;
    }
    public function getRole()
    {
        return $this->role;
    }
    public function getlegacyId()
    {
        return $this->legacyId;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getAssociatedMemberId()
    {
        return $this->associatedMemberId;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getSalt()
    {
        return $this->salt;
    }
}
