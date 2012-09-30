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
        try{
            $user = $this->userRepository->load($id);
            return UserDTOAssembler::toDTO($user);
        }catch(\InvalidArgumentException $e){
            return array();
        }
    }

    public function findUserByEmail($email){
        $users = $this->userRepository->find(array('email'=>$email));
        if(empty($users)){
            return array();
        }else{
            return UserDTOAssembler::toDTO($users[0]);
        }
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
