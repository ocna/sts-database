<?php
class Admin_Model_User
{
    protected $id;
    protected $username;
    protected $email;
    protected $firstName;
    protected $lastName;
    protected $password;
    protected $salt;
    protected $tempPassword;
    protected $lastLogin;
    protected $loginCount;
    protected $memberId;
    protected $lastUpdatedOn;
    protected $createdOn;

    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    /**
     *
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param $id field_type           
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *
     * @return the $username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     *
     * @param $username field_type           
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     *
     * @return the $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     *
     * @param $email field_type           
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     *
     * @return the $firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     *
     * @param $firstName field_type           
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     *
     * @return the $lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     *
     * @param $lastName field_type           
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getFullName()
    {
        return implode(" ", array(
            $this->firstName , $this->lastName
        ));
    }

    /**
     *
     * @return the $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     *
     * @param $password field_type           
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     *
     * @return the $salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     *
     * @param $salt field_type           
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     *
     * @return the $tempPassword
     */
    public function getTempPassword()
    {
        return $this->tempPassword;
    }

    /**
     *
     * @param $tempPassword field_type           
     */
    public function setTempPassword($tempPassword)
    {
        $this->tempPassword = $tempPassword;
    }

    /**
     *
     * @return the $lastLogin
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     *
     * @param $lastLogin field_type           
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     *
     * @return the $loginCount
     */
    public function getLoginCount()
    {
        return $this->loginCount;
    }

    /**
     *
     * @param $loginCount field_type           
     */
    public function setLoginCount($loginCount)
    {
        $this->loginCount = $loginCount;
    }

    /**
     *
     * @return the $memberId
     */
    public function getMemberId()
    {
        return $this->memberId;
    }

    /**
     *
     * @param $memberId field_type           
     */
    public function setMemberId($memberId)
    {
        $this->memberId = $memberId;
    }

    /**
     *
     * @return the $lastUpdated
     */
    public function getLastUpdatedOn()
    {
        return $this->lastUpdated;
    }

    /**
     *
     * @param $lastUpdated field_type           
     */
    public function setLastUpdatedOn($lastUpdatedOn)
    {
        $this->lastUpdatedOn = $lastUpdatedOn;
    }

    /**
     *
     * @return the $created
     */
    public function getCreatedOn()
    {
        return $this->created;
    }

    /**
     *
     * @param $created field_type           
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }
}

