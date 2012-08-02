<?php
namespace STS\Domain\User;
use STS\Domain\Entity;
/**
 * @Entity
 * @Table(name="user")
 */
class User extends Entity
{
    /**
     * @Column
     */
    private $email;
    /**
     * @Column(length=32)
     */
    private $password;
    /**
     * @Column
     */
    private $role;
    /**
     * @Column(type="datetime")
     */
    private $lastLogin;
    /**
     * @OneToOne(targetEntity="Member")
     * @JoinColumn(name="member_id", referencedColumnName="id")
     */
    private $member;

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getLastLogin()
    {
        return $this->lastLogin;
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

    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    public function setMember($member)
    {
        $this->member = $member;
        return $this;
    }
}