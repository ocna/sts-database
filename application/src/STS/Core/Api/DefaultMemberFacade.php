<?php
namespace STS\Core\Api;

use STS\Core\Cacheable;
use STS\Core\Member\MemberDtoAssembler;
use STS\Domain\Location\AreaRepository;
use STS\Domain\Location\Specification\MemberLocationSpecification;
use STS\Domain\Member\Specification\MemberByMemberAreaSpecification;
use STS\Core\Member\MemberDto;
use STS\Domain\Member\MemberRepository;
use STS\Core\Member\MongoMemberRepository;
use STS\Core\Location\MongoAreaRepository;
use STS\Core\User\MongoUserRepository;
use STS\Domain\Member;
use STS\Domain\Location\Address;
use STS\Domain\Member\Diagnosis;
use STS\Domain\Member\PhoneNumber;
use STS\Domain\User\UserRepository;

class DefaultMemberFacade implements MemberFacade
{
    /**
     * @var \STS\Core\Member\MongoMemberRepository
     */
    private $memberRepository;

    /**
     * @var \STS\Core\Location\MongoAreaRepository
     */
    private $areaRepository;

    /**
     * @var \STS\Core\User\MongoUserRepository
     */
    private $userRepository;



    public function __construct(
        MemberRepository $memberRepository,
        AreaRepository $areaRepository,
        UserRepository $userRepository
    ) {
        $this->memberRepository = $memberRepository;
        $this->areaRepository = $areaRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * getMemberById
     *
     * @param $id
     * @return MemberDto
     */
    public function getMemberById($id)
    {
        $member = $this->memberRepository->load($id);
        return MemberDtoAssembler::toDTO($member);
    }

    /**
     * getAllMembers
     *
     * @return array
     */
    public function getAllMembers()
    {
        $members = $this->memberRepository->find();
        return $this->getArrayOfDtos($members);
    }

    /**
     * getMembersMatching
     *
     * Criteria can be a single value or array of:
     *  'status'
     *  'region': valid regions names
     *  'role': valid role names
     *  'area_any': area ids (matches any field, presents for, facilitates for, etc.)
     *
     * @param $criteria
     * @return array
     */
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

        // filter by region
        if (array_key_exists('region', $criteria) && ! empty($criteria['region'])) {
            $members = $this->filterMembersByRegions($criteria['region'], $members);
        }

        // filter by  role
        if (array_key_exists('role', $criteria) && ! empty($criteria['role'])) {
            $members = $this->filterMembersByLinkedUserRoles($criteria['role'], $members);
        }

        // filter by area id
        if (array_key_exists('area_any', $criteria) && ! empty($criteria['area_any'])) {
            $members = $this->filterMembersByArea($criteria['area_any'], $members);
        }

        // filter by presents in area
        if (array_key_exists('presents_for', $criteria) && ! empty($criteria['presents_for'])) {
            $members = $this->filterMembersByPresentsIn($criteria['presents_for'], $members);
        }

        // filter by volunteer status
        if (array_key_exists('is_volunteer', $criteria)) {
            $members = $this->filterMembersByVolunteerStatus($criteria['is_volunteer'], $members);
        }

        return $this->getArrayOfDtos($members);
    }

    /**
     * @param bool $is_volunteer
     * @param $members
     *
     * @return array
     */
    private function filterMembersByVolunteerStatus($is_volunteer, $members)
    {
        $filtered_members = array();
        /** @var Member $member */
        foreach ($members as $member) {
            if ($member->isVolunteer() == $is_volunteer) {
                $filtered_members[] = $member;
            }
        }

        return $filtered_members;
    }

    private function filterMembersByRegions($regions, $members)
    {
        $filteredMembers = $members;
        if (!empty($regions)) {
            $filteredMembers = array();
            /** @var Member $member */
            foreach ($members as $member) {
                $intersection = array_intersect($regions, $member->getAllAssociatedRegions());
                if (!empty($intersection)) {
                    $filteredMembers[] = $member;
                }
            }
        }

        return $filteredMembers;
    }

    /**
     * filterMembersByArea
     *
     * Matches as long as their is at least one association to the area
     *
     * @param $areas
     * @param $members
     * @return array
     */
    private function filterMembersByArea($areas, $members)
    {
        if (!empty($areas)) {
            $filteredMembers = array();
            if (!is_array($areas)) {
                $areas = (array) $areas;
            }

            foreach ($members as $member) {
                /** @var Member $member */
                $assoc_areas = $member->getAllAssociatedAreas();
                foreach ($assoc_areas as $test) {
                    /** @var \STS\Domain\Location\Area $test */
                    if (in_array($test->getId(), $areas)) {
                        $filteredMembers[] = $member;
                    }
                }
            }
        } else {
            $filteredMembers = $members;
        }
        return $filteredMembers;
    }

    /**
     * filterMembersByArea
     *
     * Matches as long as they present in an area
     *
     * @param $areas
     * @param $members
     * @return array
     */
    private function filterMembersByPresentsIn($areas, $members)
    {
        if (!empty($areas)) {
            $filteredMembers = array();
            if (!is_array($areas)) {
                $areas = (array) $areas;
            }

            foreach ($members as $member) {
                /** @var Member $member */
                $assoc_areas = $member->getPresentsForAreas();
                foreach ($assoc_areas as $test) {
                    /** @var \STS\Domain\Location\Area $test */
                    if (in_array($test->getId(), $areas)) {
                        $filteredMembers[] = $member;
                    }
                }
            }
        } else {
            $filteredMembers = $members;
        }
        return $filteredMembers;
    }

    private function filterMembersByLinkedUserRoles($roles, $members)
    {
        // to implement get role for linked user, filter as needed
        $filteredMembers = array();
        foreach ($members as $member) {
            /** @var Member $member */
            if (in_array('ROLE_MEMBER', $roles) && is_null($member->getAssociatedUserId())) {
                $filteredMembers[] = $member;
            }

            if (! is_null($member->getAssociatedUserId())
                && (in_array('ROLE_ADMIN', $roles)
                || in_array('ROLE_COORDINATOR', $roles)
                || in_array('ROLE_FACILITATOR', $roles))
            ) {
                try {
                    $user = $this->userRepository->load($member->getAssociatedUserId());
                } catch (\InvalidArgumentException $e) {
                    continue;
                }
                if (in_array('ROLE_ADMIN', $roles)
                    && $user->getAvailableRole('ROLE_ADMIN') == $user->getRole()
                ) {
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

    public function getMemberActivities()
    {
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

    /**
     * @param $searchString
     * @param MemberLocationSpecification $spec
     * @return array
     */
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

    /**
     * getMemberByMemberAreaSpecForId
     *
     *
     * @param $id
     * @return MemberByMemberAreaSpecification
     */
    public function getMemberByMemberAreaSpecForId($id)
    {
        $member = $this->memberRepository->load($id);

        return new MemberByMemberAreaSpecification($member);
    }

    public function getMemberLocationSpecForId($id)
    {
        $member = $this->memberRepository->load($id);

        return new MemberLocationSpecification($member);
    }

    public function saveMember(
        $firstName,
        $lastName,
        $type,
        $status,
        $is_volunteer,
        $activities,
        $notes,
        $presentsFor,
        $facilitatesFor,
        $coordinatesFor,
        $userId,
        $address,
        $email,
        $dateTrained,
        $diagnosisInfo,
        $phoneNumbers
    ) {
        $member = new Member();
        $this->setMemberProperties(
            $member,
            $firstName,
            $lastName,
            $type,
            $status,
            $is_volunteer,
            $activities,
            $notes,
            $presentsFor,
            $facilitatesFor,
            $coordinatesFor,
            $userId,
            $address,
            $email,
            $dateTrained,
            $diagnosisInfo,
            $phoneNumbers
        );
        $updatedMember = $this->memberRepository->save($member);
        return MemberDtoAssembler::toDTO($updatedMember);
    }

    public function updateMember(
        $id,
        $firstName,
        $lastName,
        $type,
        $status,
        $is_volunteer,
        $activities,
        $notes,
        $presentsFor,
        $facilitatesFor,
        $coordinatesFor,
        $userId,
        $address,
        $email,
        $dateTrained,
        $diagnosisInfo,
        $phoneNumbers
    ) {
        $member = $this->memberRepository->load($id);
        $this->setMemberProperties(
            $member,
            $firstName,
            $lastName,
            $type,
            $status,
            $is_volunteer,
            $activities,
            $notes,
            $presentsFor,
            $facilitatesFor,
            $coordinatesFor,
            $userId,
            $address,
            $email,
            $dateTrained,
            $diagnosisInfo,
            $phoneNumbers
        );
        $updatedMember = $this->memberRepository->save($member);
        return MemberDtoAssembler::toDTO($updatedMember);
    }

    public function deleteMember($id)
    {
        try {
            $member = $this->memberRepository->load($id);
            if (! $member->canBeDeleted()) {
                throw new ApiException('Unable to delete member.');
            }
            return $this->memberRepository->delete($id);
        } catch (\InvalidArgumentException $e) {
            throw new ApiException('Error deleting member.', $e->getCode(), $e);
        }
    }

    /**
     * getDefaultInstance
     *
     * @access public
     * @param $mongoDb
     * @return DefaultMemberFacade
     */
    public static function getDefaultInstance($mongoDb, Cacheable $cache)
    {
        $memberRepository = new MongoMemberRepository($mongoDb, $cache);
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
        foreach ($ids as $id) {
            $areas[] = $this->areaRepository->load($id);
        }
        return $areas;
    }

    /**
     * setMemberProperties
     *
     * @param Member $member
     * @param $firstName
     * @param $lastName
     * @param $type
     * @param $status
     * @param $is_volunteer
     * @param $activities
     * @param $notes
     * @param $presentsFor
     * @param $facilitatesFor
     * @param $coordinatesFor
     * @param $userId
     * @param $address
     * @param $email
     * @param $dateTrained
     * @param $diagnosisInfo
     * @param $phoneNumbers
     */
    private function setMemberProperties(
        Member &$member,
        $firstName,
        $lastName,
        $type,
        $status,
        $is_volunteer,
        $activities,
        $notes,
        $presentsFor,
        $facilitatesFor,
        $coordinatesFor,
        $userId,
        $address,
        $email,
        $dateTrained,
        $diagnosisInfo,
        $phoneNumbers
    ) {
        /// prepeare diagnosis
        if (!in_array($diagnosisInfo['stage'], Diagnosis::getAvailableStages())) {
            $stage = null;
        } else {
            $stage = $diagnosisInfo['stage'];
        }
        $diagnosis = new Diagnosis($diagnosisInfo['date'], $stage);

        // prepare address model
        $address_object = new Address();
        $address_object->setAddress($address);

        // hydrate member fields
        $member->setFirstName($firstName)
                ->setLastName($lastName)
                ->setType($type)
                ->setStatus($status)
                ->setVolunteer($is_volunteer)
                ->setNotes($notes)
                ->setAddress($address_object)
                ->setAssociatedUserId($userId)
                ->setEmail($email)
                ->setDateTrained($dateTrained)
                ->setDiagnosis($diagnosis);

        // clear array fields, may be changed by incoming data
        $member->clearPresentsFor()
               ->clearFacilitatesFor()
               ->clearCoordinatesFor()
               ->clearActivities()
               ->clearPhoneNumbers();

        // update presentation areas
        foreach ($this->getAreasForIds($presentsFor) as $area) {
            $member->canPresentForArea($area);
        }

        // update facilitation areas
        foreach ($this->getAreasForIds($facilitatesFor) as $area) {
            $member->canFacilitateForArea($area);
        }

        // update coordinates areas
        foreach ($this->getAreasForIds($coordinatesFor) as $area) {
            $member->canCoordinateForArea($area);
        }

        // update member activities
        foreach ($activities as $activity) {
            $member->setActivity($activity);
        }

        // set member phone number after fixing format
        foreach ($phoneNumbers as $type => $number) {
            $number = preg_replace('/[-]/', '', $number);
            if (preg_match('/\d{10}/', $number)) {
                $member->addPhoneNumber(new PhoneNumber($number, $type));
            }
        }
    }
}
