<?php
namespace STS\Core\Api;
use STS\Domain\Location\Address;
use STS\Domain\School;
use STS\Core\School\SchoolDTOAssembler;
use STS\Core\School\SchoolDto;
use STS\Core\Api\SchoolFacade;
use STS\Core\School\MongoSchoolRepository;

class DefaultSchoolFacade implements SchoolFacade
{

    private $schoolRepository;
    private $areaRepository;
    public function __construct($schoolRepository)
    {
        $this->schoolRepository = $schoolRepository;
    }
    public function getSchoolById($id){
        $school = $this->schoolRepository->load($id);
        $schoolDto = SchoolDTOAssembler::toDTO($school);
        return $schoolDto;
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
        } else {
            $schools = $allSchools;
        }
        $schoolDtos = array();
        foreach ($schools as $school) {
            $schoolDtos[] = SchoolDTOAssembler::toDTO($school);
        }
        return $schoolDtos;
    }
    public function getSchoolTypes()
    {
        return array_combine(School::getTypes(), School::getTypes());
    }
    public function saveSchool($name, $areaId, $schoolType, $notes, $addressLineOne, $addressLineTwo, $city, $state,
                    $zip)
    {
        $address = new Address();
        $address->setLineOne($addressLineOne)->setLineTwo($addressLineTwo)->setCity($city)->setState($state)
            ->setZip($zip);
        $school = new School();
        $area = $this->schoolRepository->loadAreaById($areaId);
        $school->setName($name)->setNotes($notes)->setType($schoolType)->setNotes($notes)->setAddress($address)
            ->setArea($area);
        return $this->schoolRepository->save($school);
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
