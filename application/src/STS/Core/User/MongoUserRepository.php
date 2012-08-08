<?php
namespace STS\Core\User;
use STS\Domain\User;
use STS\Domain\User\UserRepository;

class MongoUserRepository implements UserRepository
{

    private $mongoDb;
    public function __construct($mongoDb)
    {
        $this->mongoDb = $mongoDb;
    }
    public function load($id)
    {
        $userData = $this->mongoDb->user->findOne(array(
                "_id" => $id
            ));
        $user = $this->mapData($userData);
        return $userData['_id'] === NULL ? null : $user;
    }
    private function mapData($userData)
    {
        $user = new User();
        $user->setId($userData['_id'])->setLegacyId($userData['legacyid'])->setEmail($userData['email'])
            ->setPassword($userData['pw'])->setSalt($userData['salt'])->setRole($userData['role'])
            ->setFirstName($userData['fname'])->setLastName($userData['lname']);
        return $user;
    }
}
