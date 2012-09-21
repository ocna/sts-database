<?php
namespace STS\Core\School;
use STS\Domain\Location\Address;
use STS\Domain\School;
use STS\Domain\School\SchoolRepository;
use STS\Domain\Location\Region;
use STS\Domain\Location\Area;

class MongoSchoolRepository implements SchoolRepository
{

    private $mongoDb;
    public function __construct($mongoDb)
    {
        $this->mongoDb = $mongoDb;
    }
    public function find()
    {
        $schools = $this->mongoDb->school->find()->sort(array(
                'name' => 1
            ));
        $returnData = array();
        foreach ($schools as $schoolData) {
            $returnData[] = $this->mapData($schoolData);
        }
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
            $areaId = $schoolData['area_id']['_id'];
            $school->setArea($this->loadAreaById($areaId));
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
    public function loadAreaById($areaId)
    {
        $mongoId = new \MongoId($areaId);
        $areaData = $this->mongoDb->area->findOne(array(
                '_id' => $mongoId
            ));
        $region = new Region();
        $region->setLegacyId($areaData['region']['legacyid'])->setName($areaData['region']['name']);
        $area = new Area();
        $area->setId($areaData['_id']->__toString())->setRegion($region)->setLegacyId($areaData['legacyid'])
            ->setName($areaData['name'])->setState($areaData['state'])->setCity($areaData['city']);
        return $area;
    }
}
