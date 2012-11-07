<?php
namespace STS\Core\School;
use STS\Domain\Location\Address;
use STS\Domain\School;
use STS\Domain\School\SchoolRepository;
use STS\Domain\Location\Region;
use STS\Domain\Location\Area;
use STS\Core\Location\MongoAreaRepository;

class MongoSchoolRepository implements SchoolRepository
{

    private $mongoDb;
    public function __construct($mongoDb)
    {
        $this->mongoDb = $mongoDb;
    }
    public function load($id)
    {
        $schoolData = $this->mongoDb->school->findOne(array(
                '_id' => new \MongoId($id)
            ));
        if ($schoolData == null) {
            throw new \InvalidArgumentException("School not found with given id: $id");
        }
        $school = $this->mapData($schoolData);
        return $school;
    }
    public function find()
    {
        $schools = $this->mongoDb->school->find()->sort(array(
                'name' => 1
            ));
        $returnData = array();
        foreach ($schools as $schoolData) {
            $returnData[strtolower($schoolData['name'])] = $this->mapData($schoolData);
        }
        ksort($returnData);
        return $returnData;
    }
    public function save($school)
    {
        if (!$school instanceof School) {
            throw new \InvalidArgumentException('Instance of School expected.');
        }
        
        $array = $school->toMongoArray();

        $id = array_shift($array);
        $array['dateCreated'] = new \MongoDate();
        $results = $this->mongoDb->school
            ->update(array(
                '_id' => new \MongoId($id)
            ), $array, array(
                'upsert' => 1, 'safe' => 1
            ));
        if (array_key_exists('upserted', $results)) {
            $school->setId($results['upserted']->__toString());
        }
        return $school;
    }
    private function mapData($schoolData)
    {
        $school = new School();
        $school->setId($schoolData['_id']->__toString())->setLegacyId($schoolData['legacyid'])
            ->setName($schoolData['name']);
        if (array_key_exists('area_id', $schoolData)) {
            $areaRepository = new MongoAreaRepository($this->mongoDb);
            $school->setArea($areaRepository->load($schoolData['area_id']['_id']));
        }
        if (array_key_exists('address', $schoolData)) {
            $address = new Address();
            $address->setLineOne($schoolData['address']['line_one'])->setLineTwo($schoolData['address']['line_two'])
                ->setCity($schoolData['address']['city'])->setState($schoolData['address']['state'])
                ->setZip($schoolData['address']['zip']);
            $school->setAddress($address);
        }
        if (array_key_exists('notes', $schoolData)) {
            $school->setNotes($schoolData['notes']);
        }
        if (array_key_exists('type', $schoolData)) {
            $school->setType($schoolData['type']);
        }
        return $school;
    }
}
