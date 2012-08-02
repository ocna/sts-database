<?php
namespace STS\Core\User;
class UserDTO
{
    private $id;
    private $email;
    private $role;
    private $memberId;

    public function __construct($id, $email, $role, $memberId)
    {
        $this->id = $id;
        $this->email = $email;
        $this->role = $role;
        $this->memberId = $memberId;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getMemberId()
    {
        return $this->memberId;
    }
    
    public function getId(){
        return $this->id;
    }
}