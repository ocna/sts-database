<?php
namespace STS\Core\Api;

use STS\Core\Member\MemberDtoAssembler;
use STS\Domain\School\Specification\MemberSchoolSpecification;
use STS\Domain\Member\Specification\MemberByMemberAreaSpecification;
use STS\Core\Member\MemberDto;
use STS\Core\Api\MemberFacade;
use STS\Core\Member\MongoMemberRepository;
use STS\Core\Location\MongoAreaRepository;
use STS\Domain\Member;
use STS\Domain\Location\Address;
use STS\Domain\Member\Diagnosis;
use STS\Domain\Member\PhoneNumber;

class DefaultMemberFacade implements MemberFacade
{
    private $memberRepository;
    private $areaRepository;
    public function __construct($memberRepository, $areaRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->areaRepository = $areaRepository;
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
    public function getDiagnosisStages()
    {
        return Diagnosis::getAvailableStages();
    }
    public function getPhoneNumberTypes()
    {
        return PhoneNumber::getAvailableTypes();
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
    public function saveMember($firstName, $lastName, $type, $status, $notes, $presentsFor, $facilitatesFor, $coordinatesFor, $userId, $addressLineOne, $addressLineTwo, $city, $state, $zip, $email, $dateTrained, $diagnosisInfo, $phoneNumbers)
    {
        $diagnosis = new Diagnosis($diagnosisInfo['date'], $diagnosisInfo['stage']);
        $address = new Address();
        $address->setLineOne($addressLineOne)->setLineTwo($addressLineTwo)->setCity($city)->setState($state)->setZip($zip);
        $member = new Member();
        $member->setFirstName($firstName)->setLastName($lastName)
               ->setType($type)->setStatus($status)->setNotes($notes)
               ->setAddress($address)->setAssociatedUserId($userId)
               ->setEmail($email)->setDateTrained($dateTrained)
               ->setDiagnosis($diagnosis);

        foreach ($this->getAreasForIds($presentsFor) as $area) {
            $member->canPresentForArea($area);
        }

        foreach ($this->getAreasForIds($facilitatesFor) as $area) {
            $member->canFacilitateForArea($area);
        }

        foreach ($this->getAreasForIds($coordinatesFor) as $area) {
            $member->canCoordinateForArea($area);
        }

        foreach ($phoneNumbers as $type => $number) {
            $number = preg_replace('/[-]/', '', $number);
            $member->addPhoneNumber(new PhoneNumber($number, $type));
        }
        $updatedMember = $this->memberRepository->save($member);
        return MemberDtoAssembler::toDTO($updatedMember);
    }

    public function deleteMember($id){
        $member = $this->memberRepository->load($id);
        if(! $member->canBeDeleted()){
            throw new ApiException('Unable to delete member.');
        }
        try{
            return $this->memberRepository->delete($id);
        }catch(\Exception $e){
            throw new ApiException('Error deleting member.', $e->getCode(), $e);
        }
    }
    public static function getDefaultInstance($config)
    {
        $mongoConfig = $config->modules->default->db->mongodb;
        $auth = $mongoConfig->username ? $mongoConfig->username . ':' . $mongoConfig->password . '@' : '';
        $mongo = new \Mongo('mongodb://' . $auth . $mongoConfig->host . ':' . $mongoConfig->port . '/' . $mongoConfig->dbname);
        $mongoDb = $mongo->selectDB($mongoConfig->dbname);
        $memberRepository = new MongoMemberRepository($mongoDb);
        $areaRepository = new MongoAreaRepository($mongoDb);
        return new DefaultMemberFacade($memberRepository, $areaRepository);
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
            $areas[] = $this->areaRepository->load($id);
        }
        return $areas;
    }
}
