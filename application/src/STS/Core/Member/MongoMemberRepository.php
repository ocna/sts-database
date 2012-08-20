<?php
namespace STS\Core\Member;
use STS\Domain\Location\Area;
use STS\Domain\Location\Region;
use STS\Domain\Member;
use STS\Domain\Member\MemberRepository;

class MongoMemberRepository implements MemberRepository
{

    private $mongoDb;
    public function __construct($mongoDb)
    {
        $this->mongoDb = $mongoDb;
    }
    public function searchByName($searchString)
    {
        $regex = new \MongoRegex("/$searchString/i");
        $members = $this->mongoDb->member->find(array(
                'fullname' => $regex
            ))->sort(array(
                'lname' => 1
            ));
        $returnData = array();
        foreach ($members as $memberData) {
            $returnData[] = $this->mapData($memberData);
        }
        return $returnData;
    }
    public function load($id)
    {
        $mongoId = new \MongoId($id);
        $memberData = $this->mongoDb->member->findOne(array(
                '_id' => $mongoId
            ));
        return $this->mapData($memberData);
    }
    private function mapData($memberData)
    {
        $member = new Member();
        $member->setId($memberData['_id']->__toString())->setLegacyId($memberData['legacyid'])
            ->setFirstName($memberData['fname'])->setLastName($memberData['lname']);
        if (array_key_exists('presents_for', $memberData)) {
            foreach ($memberData['presents_for'] as $area) {
                $areaId = $area['_id'];
                $member->canPresentForArea($this->loadAreaById($areaId));
            }
        }
        if (array_key_exists('facilitates_for', $memberData)) {
            foreach ($memberData['facilitates_for'] as $area) {
                $areaId = $area['_id'];
                $member->canFacilitateForArea($this->loadAreaById($areaId));
            }
        }
        if (array_key_exists('coordinates_for', $memberData)) {
            foreach ($memberData['coordinates_for'] as $area) {
                $areaId = $area['_id'];
                $member->canCoordinateForArea($this->loadAreaById($areaId));
            }
        }
        return $member;
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
