<?php
namespace STS\Domain;
use STS\Domain\Entity;

class User extends Entity
{
    const ROLE_ADMIN = 'admin';
    const ROLE_COORDINATOR = 'coordinator';
    const ROLE_FACILITATOR = 'facilitator';

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
            'email' => utf8_encode($this->email),
            'fname' => utf8_encode($this->firstName),
            'lname' => utf8_encode($this->lastName),
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

    public function initializePasswordIfNew($password)
    {
        if ($this->password == sha1($this->salt . $password) || is_null($password) || $password == '') {
            return $this;
        } else {
            $this->initializePassword($password);
        }
    }

    public function isRole($key)
    {
        return $this->getAvailableRole($key) == $this->getRole();
    }

    public static function getAvailableRole($key)
    {
        if (substr($key, 0, 5) != 'ROLE_') {
            throw new \InvalidArgumentException('Role key must begin with "ROLE_".');
        }
        if (!array_key_exists($key, static::getAvailableRoles())) {
            throw new \InvalidArgumentException('No such role with given key.');
        }
        $reflected = new \ReflectionClass(get_called_class());
        return $reflected->getConstant($key);
    }

    public static function getAvailableRoles()
    {
        $reflected = new \ReflectionClass(get_called_class());
        $roles = array();
        foreach ($reflected->getConstants() as $key => $value) {
            if (substr($key, 0, 5) == 'ROLE_') {
                $types[$key] = $value;
            }
        }
        return $types;
    }
}
