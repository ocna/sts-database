<?php
namespace STS\Domain;
use STS\Domain\Entity;

class User extends Entity
{

    private $email;
    private $userName;
    private $password;
    private $role;
    private $salt;
    private $member;
    public function getEmail()
    {
        return $this->email;
    }
    public function getUserName()
    {
        return $this->userName;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getRole()
    {
        return $this->role;
    }
    public function getSalt()
    {
        return $this->salt;
    }
    public function getMember()
    {
        return $this->member;
    }
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    public function setUserName($userName)
    {
        $this->userName = $userName;
        return $this;
    }
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }
    public function setMember($member)
    {
        $this->member = $member;
        return $this;
    }
}
