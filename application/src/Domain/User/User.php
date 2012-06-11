<?php
namespace STS\Domain\User\User;
use STS\Domain\Entity\Entity;
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
}