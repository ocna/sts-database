<?php
namespace STS\Core\Api;

use STS\Core\User\UserDTOAssembler;
use STS\Core\User\MongoUserRepository;
use STS\Domain\User;

class DefaultUserFacade implements UserFacade
{
    /**
     * @var MongoUserRepository $userRepository;
     */
    private $userRepository;

    /**
     * __construct
     *
     * @param $userRepository
     */
    public function __construct($userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * findUserById
     *
     * @param $id
     * @return array|\STS\Core\User\UserDTO
     */
    public function findUserById($id)
    {
        try {
            $user = $this->userRepository->load($id);
            return UserDTOAssembler::toDTO($user);
        } catch (\InvalidArgumentException $e) {
            return array();
        }
    }

    /**
     * getUserByMemberId
     *
     * @param $id
     * @return null|\STS\Core\User\UserDTO
     */
    public function getUserByMemberId($id)
    {
        $users = $this->userRepository->find(
            array('member_id' => array('_id' => new \MongoId($id)))
        );
        if (empty($users)) {
            return null;
        } else {
            return UserDTOAssembler::toDTO($users[0]);
        }
    }

    /**
     * findUserByEmail
     *
     * @param $email
     * @return array|\STS\Core\User\UserDTO
     */
    public function findUserByEmail($email)
    {
        $users = $this->userRepository->find(array('email'=>$email));
        if (empty($users)) {
            return array();
        } else {
            return UserDTOAssembler::toDTO($users[0]);
        }
    }

    /**
     * createUser
     *
     * @param $username
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $password
     * @param $role
     * @param $associatedMemberId
     * @param bool $init_password
     * @param null $salt
     * @return \STS\Core\User\UserDTO
     * @throws \Exception
     */
    public function createUser(
        $username,
        $firstName,
        $lastName,
        $email,
        $password,
        $role,
        $associatedMemberId,
        $init_password = true,
        $salt = null
    ) {
        if (!$init_password && null == $salt) {
            throw new \Exception(
                'You must provide password salt if skipping password initialization'
            );
        }

        $user = new User();
        $user->setId($username)
             ->setFirstName($firstName)
             ->setLastName($lastName)
             ->setEmail($email)
             ->setRole($role)
             ->setAssociatedMemberId($associatedMemberId)
        ;
        if ($init_password) {
            $user->initializePassword($password);
        } else {
            $user->setPassword($password)->setSalt($salt);
        }

        $newUser = $this->userRepository->save($user);
        return UserDTOAssembler::toDTO($user);
    }

    /**
     * updateUser
     *
     * @param $username
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $password
     * @param $role
     * @param $associatedMemberId
     * @return \STS\Core\User\UserDTO
     * @throws ApiException
     */
    public function updateUser(
        $username,
        $firstName,
        $lastName,
        $email,
        $password,
        $role,
        $associatedMemberId,
        $init_password = true,
        $salt = null
    ) {
        $user = $this->userRepository->load($username);
        if ($associatedMemberId != $user->getAssociatedMemberId()) {
            throw new ApiException('Can not associate user with different member.');
        }
        $user->setFirstName($firstName)
             ->setLastName($lastName)
             ->setEmail($email)
             ->setRole($role);

        if ($init_password) {
            $user->initializePassword($password);
        } else {
            $user->setPassword($password)->setSalt($salt);
        }

        $updatedUser = $this->userRepository->save($user);
        return UserDTOAssembler::toDTO($updatedUser);
    }

    /**
     * getUserRoleKey
     *
     * @param $key
     * @return mixed
     */
    public function getUserRoleKey($key)
    {
        return array_search($key, User::getAvailableRoles());
    }

    /**
     * deleteUser
     *
     * @param $id
     * @return bool
     * @throws ApiException
     */
    public function deleteUser($id)
    {
        try {
            return $this->userRepository->delete($id);
        } catch (\InvalidArgumentException $e) {
            throw new ApiException('Error deleting member.', $e->getCode(), $e);
        }
    }

    /**
     * getDefaultInstance
     *
     * @param $config
     * @return DefaultUserFacade
     */
    public static function getDefaultInstance($config)
    {
        // get configuration file settings
        $mongoConfig = $config->modules->default->db->mongodb;

        // build a DSN string
        $auth = $mongoConfig->username ? $mongoConfig->username . ':' . $mongoConfig->password . '@' : '';
        $dsn = 'mongodb://' . $auth . $mongoConfig->host . ':' . $mongoConfig->port . '/'
               . $mongoConfig->dbname;

        // connect to mongo
        // TODO look into adding error handling
        $mongo = new \Mongo($dsn);
        $mongoDb = $mongo->selectDB($mongoConfig->dbname);
        $userRepository = new MongoUserRepository($mongoDb);
        return new DefaultUserFacade($userRepository);
    }
}
