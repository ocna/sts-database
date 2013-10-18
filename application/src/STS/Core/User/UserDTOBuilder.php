<?php
namespace STS\Core\User;
use STS\Core\User\UserDTO;

class UserDTOBuilder
{

    private $id = null;
    private $email = null;
    private $firstName = null;
    private $lastName = null;
    private $role = null;
    private $legacyId = null;
    private $associatedMemberId = null;
    private $password = null;
    private $salt = null;

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

    public function withFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function withLastName($lastName)
    {
        $this->lastName = $lastName;
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

    public function withLegacyId($legacyId)
    {
        $this->legacyId = $legacyId;
        return $this;
    }

    public function withAssociatedMemberId($id)
    {
        $this->associatedMemberId = $id;
        return $this;
    }

    public function withPassword($pw) {
        $this->password = $pw;
        return $this;
    }

    public function withSalt($salt) {
        $this->salt = $salt;
        return $this;
    }
    /**
     *
     * @return $userDTO object
     */
    public function build()
    {
        return new UserDTO(
            $this->id,
            $this->email,
            $this->firstName,
            $this->lastName,
            $this->role,
            $this->legacyId,
            $this->associatedMemberId,
            $this->password,
            $this->salt
        );
    }
}
