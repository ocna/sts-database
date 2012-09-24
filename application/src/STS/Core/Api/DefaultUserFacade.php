<?php
namespace STS\Core\Api;
use STS\Core\User\UserDTOAssembler;
use STS\Core\User\MongoUserRepository;
use STS\Core\Api\UserFacade;

class DefaultUserFacade implements UserFacade
{

    private $userRepository;
    public function __construct($userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function findUserById($id)
    {
        $user = $this->userRepository->load($id);
        return UserDTOAssembler::toDTO($user);
    }
    public static function getDefaultInstance($config)
    {
        $mongoConfig = $config->modules->default->db->mongodb;
        $auth = $mongoConfig->username ? $mongoConfig->username . ':' . $mongoConfig->password . '@' : '';
        $mongo = new \Mongo(
                        'mongodb://' . $auth . $mongoConfig->host . ':' . $mongoConfig->port . '/'
                                        . $mongoConfig->dbname);
        $mongoDb = $mongo->selectDB($mongoConfig->dbname);
        $userRepository = new MongoUserRepository($mongoDb);
        return new DefaultUserFacade($userRepository);
    }
}
