<?php
namespace STS\Core\Api;

use STS\Domain\Location\Address;
use STS\Domain\School;
use STS\Core\School\SchoolDtoAssembler;
use STS\Core\School\SchoolDto;
use STS\Core\Api\SchoolFacade;
use STS\Core\School\MongoSchoolRepository;
use STS\Core\Location\MongoAreaRepository;

class DefaultSchoolFacade implements SchoolFacade
{

    private $schoolRepository;
    private $areaRepository;
    public function __construct($schoolRepository, $areaRepository)
    {
        $this->schoolRepository = $schoolRepository;
        $this->areaRepository = $areaRepository;
    }
    public function getSchoolById($id)
    {
        $school = $this->schoolRepository->load($id);
        return SchoolDtoAssembler::toDTO($school);
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
            $schoolDtos[] = SchoolDtoAssembler::toDTO($school);
        }
        return $schoolDtos;
    }
    public function getSchoolTypes()
    {
        return School::getAvailableTypes();
    }
    public function saveSchool($name, $areaId, $schoolType, $notes, $addressLineOne, $addressLineTwo, $city, $state, $zip)
    {
        $address = new Address();
        $address->setLineOne($addressLineOne)
                ->setLineTwo($addressLineTwo)
                ->setCity($city)
                ->setState($state)
                ->setZip($zip);
        $area = $this->areaRepository->load($areaId);
        $school = new School();
        $school->setName($name)
               ->setNotes($notes)
               ->setType(School::getAvailableType($schoolType))
               ->setNotes($notes)
               ->setAddress($address)
               ->setArea($area);
        $savedSchool = $this->schoolRepository->save($school);
        return SchoolDtoAssembler::toDTO($updatedSchool);
    }

     /**
      * updateSchool updates a schools values
      * 
      * @return SchoolDto
      */
    public function updateSchool($id, $name, $areaId, $schoolType, $notes, $addressLineOne, $addressLineTwo, $city, $state, $zip)
    {
        $oldSchool = $this->schoolRepository->load($id);
        $address = new Address();
        $address->setLineOne($addressLineOne)
                ->setLineTwo($addressLineTwo)
                ->setCity($city)
                ->setState($state)
                ->setZip($zip);
        $oldSchool->setName($name)
                  ->setType(School::getAvailableType($schoolType))
                  ->setNotes($notes)
                  ->setArea($this->areaRepository->load($areaId))
                  ->setAddress($address);
        $updatedSchool = $this->schoolRepository->save($oldSchool);
        return SchoolDtoAssembler::toDTO($updatedSchool);
    }

    public static function getDefaultInstance($config)
    {
        $mongoConfig = $config->modules->default->db->mongodb;
        $auth = $mongoConfig->username ? $mongoConfig->username . ':' . $mongoConfig->password . '@' : '';
        $mongo = new \Mongo('mongodb://' . $auth . $mongoConfig->host . ':' . $mongoConfig->port . '/' . $mongoConfig->dbname);
        $mongoDb = $mongo->selectDB($mongoConfig->dbname);
        $schoolRepository = new MongoSchoolRepository($mongoDb);
        $areaRepository = new MongoAreaRepository($mongoDb);
        return new DefaultSchoolFacade($schoolRepository, $areaRepository);
    }
}
