<?php
namespace STS\Core\Location;
use STS\Domain\Location\AreaRepository;
class MongoAreaRepository implements AreaRepository{

    private $mongoDb;
    public function __construct($mongoDb)
    {
        $this->mongoDb = $mongoDb;
    }

    public function load($id){
    }
}