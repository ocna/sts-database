<?php
namespace STS\Core\User;

use STS\Domain\User;
use STS\Domain\User\UserRepository;

class MongoUserRepository implements UserRepository
{
    private $collection = 'user';
    private $mongoDb;

    /**
     * @param \MongoDB $mongoDb
     */
    public function __construct($mongoDb)
    {
        $this->mongoDb = $mongoDb;
    }

    protected function getCollection()
    {
        return $this->mongoDb->selectCollection($this->collection);
    }

    /**
     * @param $id
     * @return User
     * @throws \InvalidArgumentException
     */
    public function load($id)
    {
        $userData = $this->getCollection()->findOne(array(
                "_id" => $id
            ));
        if ($userData == null) {
            throw new \InvalidArgumentException("User not found with given id: $id");
        }
        return $this->mapData($userData);
    }

    /**
     * find
     *
     * @param $criteria
     * @return array
     */
    public function find($criteria)
    {
        $userData = $this->getCollection()->find($criteria);
        $users = array();
        if ($userData != null) {
            foreach ($userData as $data) {
                $users[] = $this->mapData($data);
            }
        }
        return $users;
    }

    /**
     * save
     *
     * @param $user
     * @return User
     * @throws \InvalidArgumentException
     */
    public function save($user)
    {
        if (!$user instanceof User) {
            throw new \InvalidArgumentException('Instance of User expected.');
        }
        $user->markUpdated();
        $array = $user->toMongoArray();
        $results = $this->getCollection()->update(
            array('_id' => $array['_id']),
            $array,
            array('upsert' => 1, 'safe' => 1)
        );

        if (array_key_exists('upserted', $results)) {
            $user->setId($results['upserted']->__toString());
        }

        return $user;
    }

    /**
     * delete
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $results = $this->getCollection()->remove(
            array('_id' => $id),
            array('justOne' => true, 'safe' => true)
        );

        if (1 == $results['ok']) {
            return true;
        }
        return false;
    }

    /**
     * mapData
     *
     * @param $userData
     * @return User
     */
    private function mapData($userData)
    {
        $user = new User();
        $user->setId($userData['_id'])
            ->setLegacyId($userData['legacyid'])
            ->setEmail($userData['email'])
            ->setPassword($userData['pw'])
            ->setSalt($userData['salt'])
            ->setRole($userData['role'])
            ->setFirstName($userData['fname'])
            ->setLastName($userData['lname']);

        if (array_key_exists('member_id', $userData)) {
            $user->setAssociatedMemberId($userData['member_id']['_id']->__toString());
        }
        return $user;
    }
}
