<?php
namespace STS\Core\Api;

use STS\Core\Member\MemberDtoAssembler;
use STS\Domain\School\Specification\MemberSchoolSpecification;
use STS\Domain\Member\Specification\MemberByMemberAreaSpecification;
use STS\Core\Member\MemberDto;
use STS\Core\Api\MemberFacade;
use STS\Core\Member\MongoMemberRepository;
use STS\Core\Location\MongoAreaRepository;
use STS\Core\User\MongoUserRepository;
use STS\Domain\Member;
use STS\Domain\Location\Address;
use STS\Domain\Member\Diagnosis;
use STS\Domain\Member\PhoneNumber;

class DefaultMemberFacade implements MemberFacade
{
    private $memberRepository;
    private $areaRepository;
    public function __construct($memberRepository, $areaRepository, $userRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->areaRepository = $areaRepository;
        $this->userRepository = $userRepository;
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
    public function getMembersMatching($criteria)
    {
        if (empty($criteria)) {
            return $this->getAllMembers();
        }
        $query = array();
        if (array_key_exists('status', $criteria) && ! empty($criteria['status'])) {
            $in = array();
            foreach ($criteria['status'] as $key) {
                $in[] = Member::getAvailableStatus($key);
            }
            $query = array(
                        'status' => array('$in'=>$in),
                );
        }
        $members = $this->memberRepository->find($query);
        if (array_key_exists('region', $criteria) && ! empty($criteria['region'])) {
            $members = $this->filterMembersByRegions($criteria['region'], $members);
        }
        if (array_key_exists('role', $criteria) && ! empty($criteria['role'])) {
            $members = $this->filterMembersByLinkedUserRoles($criteria['role'], $members);
        }
        return $this->getArrayOfDtos($members);
    }

    private function filterMembersByRegions($regions, $members)
    {   $filteredMembers = $members;
        if (!empty($regions)) {
            $filteredMembers = array();
            foreach ($members as $member) {
                $intersection = array_intersect($regions, $member->getAllAssociatedRegions());
                if (! empty($intersection)) {
                    $filteredMembers[] = $member;
                }
            }
        }
            return $filteredMembers;

    }

    private function filterMembersByLinkedUserRoles($roles, $members)
    {
        //to implement get role for linked user, filter as needed
        $filteredMembers = array();
        foreach ($members as $member){
            if (in_array('ROLE_MEMBER', $roles) && is_null($member->getAssociatedUserId())) {
                $filteredMembers[] = $member;
            }
            if (! is_null($member->getAssociatedUserId()) && (in_array('ROLE_ADMIN', $roles)||in_array('ROLE_COORDINATOR', $roles)||in_array('ROLE_FACILITATOR', $roles))) {
                try{
                    $user = $this->userRepository->load($member->getAssociatedUserId());
                } catch (\InvalidArgumentException $e) {
                    continue;
                }
                if (in_array('ROLE_ADMIN', $roles) && $user->getAvailableRole('ROLE_ADMIN') == $user->getRole()) {
                    $filteredMembers[] = $member;
                }
                if (in_array('ROLE_COORDINATOR', $roles) && $user->isRole('ROLE_COORDINATOR')) {
                    $filteredMembers[] = $member;
                }
                if (in_array('ROLE_FACILITATOR', $roles) && $user->isRole('ROLE_FACILITATOR')) {
                    $filteredMembers[] = $member;
                }
            }
        }
        return $filteredMembers;
    }

    public function getMemberTypes()
    {
        return Member::getAvailableTypes();
    }
    public function getMemberTypeKey($key)
    {
        return array_search($key, Member::getAvailableTypes());
    }
    public function getMemberStatusKey($key)
    {
        return array_search($key, Member::getAvailableStatuses());
    }
    public function getMemberStatuses()
    {
        return Member::getAvailableStatuses();
    }
    public function getMemberActivities() {
        return Member::getAvailableActivities();
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
        $member = new Member();
        $this->setMemberProperties($member, $firstName, $lastName, $type, $status, $notes, $presentsFor, $facilitatesFor, $coordinatesFor, $userId, $addressLineOne, $addressLineTwo, $city, $state, $zip, $email, $dateTrained, $diagnosisInfo, $phoneNumbers);
        $updatedMember = $this->memberRepository->save($member);
        return MemberDtoAssembler::toDTO($updatedMember);
    }

    public function updateMember($id, $firstName, $lastName, $type, $status, $notes, $presentsFor, $facilitatesFor, $coordinatesFor, $userId, $addressLineOne, $addressLineTwo, $city, $state, $zip, $email, $dateTrained, $diagnosisInfo, $phoneNumbers)
    {
        $member = $this->memberRepository->load($id);
        $this->setMemberProperties($member, $firstName, $lastName, $type, $status, $notes, $presentsFor, $facilitatesFor, $coordinatesFor, $userId, $addressLineOne, $addressLineTwo, $city, $state, $zip, $email, $dateTrained, $diagnosisInfo, $phoneNumbers);
        $updatedMember = $this->memberRepository->save($member);
        return MemberDtoAssembler::toDTO($updatedMember);
    }

    public function deleteMember($id){
        try{
            $member = $this->memberRepository->load($id);
            if(! $member->canBeDeleted()){
                throw new ApiException('Unable to delete member.');
            }
            return $this->memberRepository->delete($id);
        }catch(\InvalidArgumentException $e){
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
        $userRepository = new MongoUserRepository($mongoDb);
        return new DefaultMemberFacade($memberRepository, $areaRepository, $userRepository);
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

    private function setMemberProperties(&$member, $firstName, $lastName, $type, $status, $notes, $presentsFor, $facilitatesFor, $coordinatesFor, $userId, $addressLineOne, $addressLineTwo, $city, $state, $zip, $email, $dateTrained, $diagnosisInfo, $phoneNumbers)
    {
        if (! in_array($diagnosisInfo['stage'], Diagnosis::getAvailableStages())) {
            $stage = null;
        } else {
            $stage = $diagnosisInfo['stage'];
        }
        $diagnosis = new Diagnosis($diagnosisInfo['date'], $stage);
        $address = new Address();
        $address->setLineOne($addressLineOne)->setLineTwo($addressLineTwo)->setCity($city)->setState($state)->setZip($zip);
        $member->setFirstName($firstName)->setLastName($lastName)
               ->setType($type)->setStatus($status)->setNotes($notes)
               ->setAddress($address)->setAssociatedUserId($userId)
               ->setEmail($email)->setDateTrained($dateTrained)
               ->setDiagnosis($diagnosis)->clearPresentsFor()->clearFacilitatesFor()->clearCoordinatesFor()->clearPhoneNumbers();

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
            if(preg_match('/\d{10}/', $number)){
                $member->addPhoneNumber(new PhoneNumber($number, $type));
            }
        }
    }
}
