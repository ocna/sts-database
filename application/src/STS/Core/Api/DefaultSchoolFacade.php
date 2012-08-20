<?php
namespace STS\Core\Api;
use STS\Core\School\SchoolDto;
use STS\Core\Api\SchoolFacade;
use STS\Core\School\MongoSchoolRepository;

class DefaultSchoolFacade implements SchoolFacade
{

    private $schoolRepository;
    public function __construct($schoolRepository)
    {
        $this->schoolRepository = $schoolRepository;
    }
    public function getSchoolsForSpecification($spec)
    {
        $allSchools = $this->schoolRepository->find();
        if ($spec !== null) {
            $schools = array();
            foreach ($allSchools as $school) {
                if ($spec->isSatisfiedBy($school)) {
                    $schools[] = $school;
                }
            }
        }else{
            $schools = $allSchools;
        }
        $schoolDtos = array();
        foreach ($schools as $school) {
            $schoolDtos[] = new SchoolDto($school->getId(), $school->getLegacyId(), $school->getName());
        }
        return $schoolDtos;
    }
    public static function getDefaultInstance($config)
    {
        $mongoConfig = $config->modules->default->db->mongodb;
        $auth = $mongoConfig->username ? $mongoConfig->username . ':' . $mongoConfig->password . '@' : '';
        $mongo = new \Mongo(
                        'mongodb://' . $auth . $mongoConfig->host . ':' . $mongoConfig->port . '/'
                                        . $mongoConfig->dbname);
        $mongoDb = $mongo->selectDB($mongoConfig->dbname);
        $schoolRepository = new MongoSchoolRepository($mongoDb);
        return new DefaultSchoolFacade($schoolRepository);
    }
}
