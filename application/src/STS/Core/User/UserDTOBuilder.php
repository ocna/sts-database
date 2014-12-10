<?php
namespace STS\Core\User;

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
     * @param $id
     *
     * @return $this
     */
    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param $email
     *
     * @return $this
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
     * @param $role
     *
     * @return $this
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

    public function withPassword($pw)
    {
        $this->password = $pw;
        return $this;
    }

    public function withSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * @return UserDTO
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
