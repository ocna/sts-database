<?php
namespace STS\Core\Api;

use STS\Domain\Location\Address;
use STS\Domain\School;
use STS\Core\School\SchoolDtoAssembler;
use STS\Core\School\SchoolDto;
use \STS\Domain\School\Specification\MemberSchoolSpecification;
use STS\Core\School\MongoSchoolRepository;
use STS\Core\Location\MongoAreaRepository;

class DefaultSchoolFacade implements SchoolFacade
{
    /**
     * @var MongoSchoolRepository
     */
    private $schoolRepository;

    /**
     * @var MongoAreaRepository
     */
    private $areaRepository;

	/**
	 * @param MongoSchoolRepository $schoolRepository
	 * @param MongoAreaRepository $areaRepository
	 */
    public function __construct($schoolRepository, $areaRepository)
    {
        $this->schoolRepository = $schoolRepository;
        $this->areaRepository = $areaRepository;
    }

    /**
     * getSchoolById
     *
     * @param $id
     * @return SchoolDto
     */
    public function getSchoolById($id)
    {
        $school = $this->schoolRepository->load($id);
        return SchoolDtoAssembler::toDTO($school);
    }

    /**
     * getSchoolsForSpecification
     *
     * NOTE: not sure where this is used.
     *
     * @param MemberSchoolSpecification $spec
     * @return array
     */
    public function getSchoolsForSpecification($spec = null)
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

        return $this->toDtos($schools);
    }

    /**
     * toDtos
     *
     * Return an array of SchoolsDTOs from repository results;
     *
     * @param $schools
     * @return array
     */
    private function toDtos($schools)
    {
        $schoolDtos = array();
        foreach ($schools as $school) {
            $schoolDtos[] = SchoolDtoAssembler::toDTO($school);
        }
        return $schoolDtos;
    }

    /**
     * getSchoolsMatching
     *
     * Return an array of schools that match some predefined criteria. Available criteria array:
     *   'region': a valid region name
     *
     * @param $criteria
     * @return mixed
     */
    public function getSchoolsMatching($criteria)
    {
        // get DTOs
        $schools = $this->getAllSchools();

        // filter by region
        if (isset($criteria['region']) && !empty($criteria['region'])) {
            $schools = $this->filterSchoolsByRegions($criteria['region'], $schools);
        }

        // filter by type of school
        if (isset($criteria['type']) && !empty($criteria['type'])) {
            $schools = $this->filterSchoolsByTypes($criteria['type'], $schools);
        }

        // filter by area
        if (isset($criteria['area']) && !empty($criteria['area'])) {
            $schools = $this->filterSchoolsByAreas($criteria['area'], $schools);
        }
        return $schools;
    }

    /**
     * getAllSchools
     *
     * @return array
     */
    public function getAllSchools()
    {
        return $this->getSchoolsForSpecification(null);
    }

    /**
     * filterSchoolsByRegions
     *
     * @param $regions
     * @param $schools
     * @return array
     */
    public function filterSchoolsByRegions($regions, $schools)
    {
        if (!empty($regions)) {
            $schools = array_filter($schools, function(SchoolDto $school) use ($regions) {
                return in_array($school->getRegionName(), $regions, true);
            });
        }

        return $schools;
    }

    /**
     * filterSchoolsByTypes
     *
     * @param $types
     * @param $schools
     * @return array
     */
    public function filterSchoolsByTypes($types, $schools)
    {
        if (!empty($types)) {
            $schools = array_filter($schools, function(SchoolDto $school) use ($types) {
                return in_array($school->getTypeKey(), $types, true);
            });
        }

        return $schools;
    }
    /**
     * filterSchoolsByAreas
     *
     * @param $areas
     * @param $schools
     * @return array
     */
    public function filterSchoolsByAreas($areas, $schools)
    {
        if (!empty($areas)) {
            if (!is_array($areas)) {
                $areas = (array) $areas;
            }
            $schools = array_filter($schools, function(SchoolDto $school) use ($areas) {
                return in_array($school->getAreaId(), $areas, true);
            });
        }

        return $schools;
    }


    /**
     * getSchoolTypes
     *
     * @return array
     */
    public function getSchoolTypes()
    {
        return School::getAvailableTypes();
    }

    /**
     * saveSchool
     *
     * @param $name
     * @param $areaId
     * @param $schoolType
     * @param $notes
     * @param $addressLineOne
     * @param $addressLineTwo
     * @param $city
     * @param $state
     * @param $zip
     * @return SchoolDto
     */
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
        return SchoolDtoAssembler::toDTO($savedSchool);
    }

    /**
     * updateSchool
     *
     * updates a schools values
     *
     * @param $id
     * @param $name
     * @param $areaId
     * @param $schoolType
     * @param $notes
     * @param $addressLineOne
     * @param $addressLineTwo
     * @param $city
     * @param $state
     * @param $zip
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

    /**
     * getDefaultInstance
     *
     * @param $config
     * @return DefaultSchoolFacade
     */
    public static function getDefaultInstance($config)
    {
        $mongoConfig = $config->modules->default->db->mongodb;
        $auth = $mongoConfig->username ? $mongoConfig->username . ':' . $mongoConfig->password . '@' : '';
        $mongo = new \MongoClient('mongodb://' . $auth . $mongoConfig->host . ':' . $mongoConfig->port . '/' . $mongoConfig->dbname);
        $mongoDb = $mongo->selectDB($mongoConfig->dbname);
        $schoolRepository = new MongoSchoolRepository($mongoDb);
        $areaRepository = new MongoAreaRepository($mongoDb);
        return new DefaultSchoolFacade($schoolRepository, $areaRepository);
    }
}
