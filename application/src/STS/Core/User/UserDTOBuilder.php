<?php
namespace STS\Core\User;
use STS\Core\User\UserDTO;
class UserDTOBuilder
{
    private $id = null;
    private $email = null;
    private $role = null;
    private $memberId = null;

    /**
     * sets up the id UserDTOBuilder property
     *
     * @return $userDTOBuilder object
     */
    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * sets up the email UserDTOBuilder property
     *
     * @return $userDTOBuilder object
     */
    public function withEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * sets up the role UserDTOBuilder property
     *
     * @return $userDTOBuilder object
     */
    public function withRole($role)
    {
        $this->role = $role;
        return $this;
    }

    public function withMemberId($memberId)
    {
        $this->memberId = $memberId;
        return $this;
    }

    /**
     *
     * @return $userDTO object
     */
    public function build()
    {
        return new UserDTO($this->id, $this->email, $this->role, $this->memberId);
    }
}