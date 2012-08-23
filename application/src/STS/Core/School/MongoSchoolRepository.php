<?php
namespace STS\Core\School;
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
    private function mapData($schoolData)
    {
        $school = new School();
        $school->setId($schoolData['_id']->__toString())->setLegacyId($schoolData['legacyid'])
            ->setName($schoolData['name']);
        if (array_key_exists('area_id', $schoolData)) {
            $areaId = $schoolData['area_id']['_id'];
            $school->setArea($this->loadAreaById($areaId));
        }
        return $school;
    }
    private function loadAreaById($areaId)
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
