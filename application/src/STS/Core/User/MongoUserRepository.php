<?php
namespace STS\Core\User;
use STS\Domain\User\UserRepository;

class MongoUserRepository implements UserRepository
{
    public function __construct($mongoDb){
        
    }
    
    public function load($id)
    {
        // TODO: Auto-generated method stub
    }
    public function find($criteria)
    {
        // TODO: Auto-generated method stub
    }
}
