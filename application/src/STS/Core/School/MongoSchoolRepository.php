<?php
namespace STS\Core\School;
use STS\Domain\School;
use STS\Domain\School\SchoolRepository;

class MongoSchoolRepository implements SchoolRepository
{

    private $mongoDb;
    public function __construct($mongoDb)
    {
        $this->mongoDb = $mongoDb;
    }
    public function find()
    {
        $schools = $this->mongoDb->school->find();
        $returnData = array();
        foreach ($schools as $schoolData) {
            $returnData[] = $this->mapData($schoolData);
        }
        return $returnData;
    }
    private function mapData($schoolData)
    {
        $school = new School();
        $school->setId($schoolData['_id'])->setLegacyId($schoolData['legacyid'])->setName($schoolData['name']);
        return $school;
    }
}
