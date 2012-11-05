<?php
namespace STS\Domain;
use STS\Domain\Entity;

class User extends Entity
{
    private $email;
    private $password;
    private $role;
    private $salt;
    private $firstName;
    private $lastName;
    private $legacyId;
    private $associatedMemberId;
    public function toMongoArray()
    {
        $array = array(
            '_id' => $this->id,
            'email' => $this->email,
            'fname' => $this->firstName,
            'lname' => $this->lastName,
            'legacyid' => $this->legacyId,
            'role' => $this->role,
            'pw' => $this->password,
            'salt' => $this->salt,
            'member_id' => array(
                "_id" => new \MongoId($this->associatedMemberId)
            )
        );
        return $array;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
    public function getRole()
    {
        return $this->role;
    }
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }
    public function getSalt()
    {
        return $this->salt;
    }
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }
    public function getFirstName()
    {
        return $this->firstName;
    }
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }
    public function getLastName()
    {
        return $this->lastName;
    }
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }
    public function getLegacyId()
    {
        return $this->legacyId;
    }
    public function setLegacyId($legacyId)
    {
        $this->legacyId = $legacyId;

        return $this;
    }
    public function getAssociatedMemberId()
    {
        return $this->associatedMemberId;
    }
    public function setAssociatedMemberId($associatedMemberId)
    {
        $this->associatedMemberId = $associatedMemberId;

        return $this;
    }
    public function initializePassword($newPassword)
    {
        $this->salt = md5(time() . $newPassword . uniqid());
        $this->password = sha1($this->salt . $newPassword);

        return $this;
    }
}
