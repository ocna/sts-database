<?php
namespace STS\Core\Member;
use STS\Domain\Location\Area;
use STS\Domain\Location\Region;
use STS\Domain\Location\Address;
use STS\Domain\Member;
use STS\Domain\Member\MemberRepository;

class MongoMemberRepository implements MemberRepository
{

    private $mongoDb;
    public function __construct($mongoDb)
    {
        $this->mongoDb = $mongoDb;
    }
    public function find()
    {
        $memberData = $this->mongoDb->member->find()->sort(array(
                'lname' => 1
            ));
        return $this->mapMultiple($memberData);
    }
    public function save($member)
    {}
    public function searchByName($searchString)
    {
        $regex = new \MongoRegex("/$searchString/i");
        $memberData = $this->mongoDb->member->find(array(
                'fullname' => $regex
            ))->sort(array(
                'lname' => 1
            ));
        return $this->mapMultiple($memberData);
    }
    public function load($id)
    {
        $mongoId = new \MongoId($id);
        $memberData = $this->mongoDb->member->findOne(array(
                '_id' => $mongoId
            ));
        return $this->mapData($memberData);
    }
    private function mapMultiple($memberData)
    {
        $objects = array();
        foreach ($memberData as $data) {
            $objects[] = $this->mapData($data);
        }
        return $objects;
    }
    private function mapData($memberData)
    {
        $member = new Member();
        $member->setId($memberData['_id']->__toString())->setLegacyId($memberData['legacyid'])
            ->setFirstName($memberData['fname'])->setLastName($memberData['lname']);
        if (array_key_exists('type', $memberData)) {
            $member->setType($memberData['type']);
        }
        if (array_key_exists('notes', $memberData)) {
            $member->setNotes($memberData['notes']);
        }
        if (array_key_exists('user_id', $memberData)) {
            $member->setAssociatedUserId($memberData['user_id']);
        }
        if (array_key_exists('status', $memberData)) {
            $member->setStatus($memberData['status']);
        }else{
            $member->setStatus(Member::STATUS_ACTIVE);
        }
        if (array_key_exists('address', $memberData)) {
            $address = new Address();
            $address->setLineOne($memberData['address']['line_one'])->setLineTwo($memberData['address']['line_two'])
                ->setCity($memberData['address']['city'])->setState($memberData['address']['state'])
                ->setZip($memberData['address']['zip']);
            $member->setAddress($address);
        }
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
