<?php
/**
 *
 * @category    STS
 * @package     Core
 * @subpackage	Api
 */
namespace STS\Core\Api;
use STS\Core\User\MongoUserRepository;
use STS\Core\User\UserDTOAssembler;
use STS\Domain\User;

class DefaultAuthFacade implements AuthFacade
{
	/**
	 * @var MongoUserRepository
	 */
    private $userRepository;

	/**
	 * @param MongoUserRepository $userRepository
	 */
    public function __construct($userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function authenticate($userName, $password)
    {
        try {
            $user = $this->userRepository->load($userName);
        } catch (\InvalidArgumentException $e) {
            throw new ApiException('User not found for given user name.', ApiException::FAILURE_USER_NOT_FOUND, $e);
        }
        if ($user->getPassword() != $this->hashPassword($user, $password)) {
            throw new ApiException('Credentials are invalid for given user.', ApiException::FAILURE_CREDENTIAL_INVALID);
        }
        return UserDTOAssembler::toDTO($user);
    }

	/**
	 * @param User $user
	 * @param $password
	 *
	 * @return string
	 */
    private function hashPassword($user, $password)
    {
        return sha1($user->getSalt() . $password);
    }
    public static function getDefaultInstance($config)
    {
        $mongoConfig = $config->modules->default->db->mongodb;
        $auth = $mongoConfig->username ? $mongoConfig->username . ':' . $mongoConfig->password . '@' : '';
        $mongo = new \MongoClient(
                        'mongodb://' . $auth . $mongoConfig->host . ':' . $mongoConfig->port . '/'
                                        . $mongoConfig->dbname);
        $mongoDb = $mongo->selectDB($mongoConfig->dbname);
        $userRepository = new MongoUserRepository($mongoDb);
        return new DefaultAuthFacade($userRepository);
    }

    public function generateTemporaryPassword()
    {
        return substr(md5(uniqid().time()), 0, 8);
    }
}
