<?php
namespace STS\Core\Api;
use STS\Core\Member\MemberDtoAssembler;
use STS\Domain\School\Specification\MemberSchoolSpecification;
use STS\Domain\Member\Specification\MemberByMemberAreaSpecification;
use STS\Core\Member\MemberDto;
use STS\Core\Api\MemberFacade;
use STS\Core\Member\MongoMemberRepository;
use STS\Domain\Member;
use STS\Domain\Location\Address;

class DefaultMemberFacade implements MemberFacade
{
    private $memberRepository;
    public function __construct($memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }
    public function getMemberById($id)
    {
        $member = $this->memberRepository->load($id);

        return MemberDtoAssembler::toDTO($member);
    }
    public function getAllMembers()
    {
        $members = $this->memberRepository->find();

        return $this->getArrayOfDtos($members);
    }
    public function getMemberTypes()
    {
        return Member::getAvailableTypes();
    }
    public function getMemberStatuses()
    {
        return Member::getAvailableStatuses();
    }
    public function searchForMembersByNameWithSpec($searchString, $spec)
    {
        $foundMembers = $this->memberRepository->searchByName($searchString);
        if ($spec !== null) {
            $members = array();

            foreach ($foundMembers as $member) {
                if ($spec->isSatisfiedBy($member)) {
                    $members[] = $member;
                }
            }
        } else {
            $members = $foundMembers;
        }

        return $this->getArrayOfDtos($members);
    }
    public function getMemberByMemberAreaSpecForId($id)
    {
        $member = $this->memberRepository->load($id);

        return new MemberByMemberAreaSpecification($member);
    }
    public function getMemberSchoolSpecForId($id)
    {
        $member = $this->memberRepository->load($id);

        return new MemberSchoolSpecification($member);
    }
    public function saveMember($firstName, $lastName, $type, $status, $notes, $presentsFor, $facilitatesFor, $coordinatesFor, $userId, $addressLineOne, $addressLineTwo, $city, $state, $zip)
    {
        $address = new Address();
        $address->setLineOne($addressLineOne)->setLineTwo($addressLineTwo)->setCity($city)->setState($state)->setZip($zip);
        $member = new Member();
        $member->setFirstName($firstName)->setLastName($lastName)->setType($type)->setStatus($status)->setNotes($notes)->setAddress($address)->setAssociatedUserId($userId);

        foreach ($this->getAreasForIds($presentsFor) as $area) {
            $member->canPresentForArea($area);
        }

        foreach ($this->getAreasForIds($facilitatesFor) as $area) {
            $member->canFacilitateForArea($area);
        }

        foreach ($this->getAreasForIds($coordinatesFor) as $area) {
            $member->canCoordinateForArea($area);
        }
        $updatedMember = $this->memberRepository->save($member);
        return MemberDtoAssembler::toDTO($updatedMember);
    }
    public static function getDefaultInstance($config)
    {
        $mongoConfig = $config->modules->default->db->mongodb;
        $auth = $mongoConfig->username ? $mongoConfig->username . ':' . $mongoConfig->password . '@' : '';
        $mongo = new \Mongo('mongodb://' . $auth . $mongoConfig->host . ':' . $mongoConfig->port . '/' . $mongoConfig->dbname);
        $mongoDb = $mongo->selectDB($mongoConfig->dbname);
        $memberRepository = new MongoMemberRepository($mongoDb);

        return new DefaultMemberFacade($memberRepository);
    }
    private function getArrayOfDtos($array)
    {
        $dtos = array();

        foreach ($array as $member) {
            $dtos[] = MemberDtoAssembler::toDTO($member);
        }

        return $dtos;
    }

    private function getAreasForIds($ids)
    {
        $areas = array();
        foreach ($ids as $id){
            $areas[] = $this->memberRepository->loadAreaById($id);
        }
        return $areas;
    }
}
