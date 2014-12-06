<?php
namespace STS\Core\Member;
use STS\Domain\Location\Address;
use STS\Domain\Member;
use STS\Domain\Member\MemberRepository;
use STS\Core\Location\MongoAreaRepository;
use STS\Core\User\MongoUserRepository;
use STS\Domain\Member\Diagnosis;
use STS\Domain\Member\PhoneNumber;

class MongoMemberRepository implements MemberRepository
{
	/**
	 * @var \MongoDb
	 */
    private $mongoDb;

	/**
	 * @param \MongoDb $mongoDb
	 */
    public function __construct($mongoDb)
    {
        $this->mongoDb = $mongoDb;
    }

    public function find($query = array())
    {
        $memberData = $this->mongoDb->selectCollection('member')->find($query)->sort(array(
                'lname' => 1
            ));
        return $this->mapMultiple($memberData);
    }

    /**
     * Member
     * @param Member $member
     * @return Member
     * @throws \InvalidArgumentException
     */
    public function save($member)
    {
        if (!$member instanceof Member) {
            throw new \InvalidArgumentException('Instance of Member expected.');
        }

        if(is_null($member->getId())){
            $member->markCreated();
        }else{
            $member->markUpdated();
        }
        $array = $member->toMongoArray();

        $id = array_shift($array);
        $results = $this->mongoDb->selectCollection('member')
            ->update(array(
                '_id' => new \MongoId($id)
            ), $array, array(
                'upsert' => 1, 'safe' => 1
            ));
        if (array_key_exists('upserted', $results)) {
	        /** @var \MongoId $id */
	        $id = $results['upserted'];
            $member->setId($id->__toString());
        }
        return $member;
    }

    public function searchByName($searchString)
    {
        $regex = new \MongoRegex("/$searchString/i");
        $memberData = $this->mongoDb->selectCollection('member')->find(array(
                'fullname' => $regex
            ))->sort(array(
                'lname' => 1
            ));
        return $this->mapMultiple($memberData);
    }

    /**
     * load
     *
     * Retrieve a record by id
     *
     * @param $id
     * @return Member
     * @throws \InvalidArgumentException
     */
    public function load($id)
    {
        $mongoId = new \MongoId($id);
        $memberData = $this->mongoDb->selectCollection('member')->findOne(array(
                '_id' => $mongoId
            ));
        if (is_null($memberData)) {
            throw new \InvalidArgumentException('Member not found for given id.');
        }

        return $this->mapData($memberData);
    }

    public function delete($id)
    {
        $mongoId = new \MongoId($id);
        $results = $this->mongoDb->selectCollection('member')->remove(array(
                '_id' => $mongoId
            ), array('justOne'=>true, 'safe'=>true));
        if($results['ok']==1){
        return true;
        }
        return false;
    }

    private function mapMultiple($memberData)
    {
        $objects = array();
        foreach ($memberData as $data) {
            $objects[] = $this->mapData($data);
        }
        return $objects;
    }

    /**
     * mapData
     *
     * @param $memberData
     * @return Member
     */
    private function mapData($memberData)
    {
        $member = new Member();
	    /** @var \MongoId $id */
	    $id = $memberData['_id'];
        $member->setId($id->__toString())
               ->setLegacyId($memberData['legacyid'])
               ->setFirstName($memberData['fname'])
               ->setLastName($memberData['lname']);
        if (array_key_exists('dateCreated', $memberData)) {
            $member->setCreatedOn(strtotime(date('Y-M-d h:i:s', $memberData['dateCreated']->sec)));
        }

        if (array_key_exists('dateUpdated', $memberData)) {
            $member->setUpdatedOn(strtotime(date('Y-M-d h:i:s', $memberData['dateUpdated']->sec)));
        }

        if (array_key_exists('type', $memberData)) {
            $member->setType($memberData['type']);
        }

        if (array_key_exists('notes', $memberData)) {
            $member->setNotes($memberData['notes']);
        }

        if (array_key_exists('user_id', $memberData)) {
            $member->setAssociatedUserId($memberData['user_id']);
        }

        if (array_key_exists('date_trained', $memberData) && ! is_null($memberData['date_trained'])) {
            $member->setDateTrained(date('Y-M-d h:i:s', $memberData['date_trained']->sec));
        }

        if (array_key_exists('status', $memberData)) {
            $member->setStatus($memberData['status']);
        } else {
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
            $areaRepository = new MongoAreaRepository($this->mongoDb);
            foreach ($memberData['presents_for'] as $area) {
                $areaId = $area['_id'];
                $member->canPresentForArea($areaRepository->load($areaId));
            }
        }

        if (array_key_exists('facilitates_for', $memberData)) {
            $areaRepository = new MongoAreaRepository($this->mongoDb);
            foreach ($memberData['facilitates_for'] as $area) {
                $areaId = $area['_id'];
                $member->canFacilitateForArea($areaRepository->load($areaId));
            }
        }

        if (array_key_exists('coordinates_for', $memberData)) {
            $areaRepository = new MongoAreaRepository($this->mongoDb);
            foreach ($memberData['coordinates_for'] as $area) {
                $areaId = $area['_id'];
                $member->canCoordinateForArea($areaRepository->load($areaId));
            }
        }

        if (array_key_exists('email', $memberData)) {
            $member->setEmail($memberData['email']);
        } else {
            if (array_key_exists('user_id', $memberData) && isset($memberData['user_id'])) {
                $userRepository = new MongoUserRepository($this->mongoDb);
                $user = $userRepository->load($memberData['user_id']);
                $member->setEmail($user->getEmail());
            }
        }

        if (array_key_exists('diagnosis', $memberData)) {
            $diagnosis = $memberData['diagnosis'];
            $diagnosisDate = array_key_exists('date', $diagnosis) && ! is_null($diagnosis['date']) ? date('Y-M-d h:i:s', $diagnosis['date']->sec) : null;
            $diagnosisStage = array_key_exists('stage', $diagnosis) ? $diagnosis['stage'] : null;
            $member->setDiagnosis(
                new Diagnosis($diagnosisDate, $diagnosisStage)
            );
        }

        if (array_key_exists('phone_numbers', $memberData)) {
            foreach ($memberData['phone_numbers'] as $phoneNumber) {
                $member->addPhoneNumber(
                    new PhoneNumber(
                        $phoneNumber['number'],
                        $phoneNumber['type']
                    )
                );
            }
        }

        if (array_key_exists('activities', $memberData)) {
            foreach ($memberData['activities'] as $activity) {
                $member->setActivity($activity);
            }
        }

        $member->setCanBeDeleted($this->canMemberBeDeleted($member->getId()));
        return $member;
    }

    /**
     * canMemberBeDeleted
     *
     * Protect members with presentations from being deleted.
     *
     * @param $id
     * @return bool
     */
    private function canMemberBeDeleted($id) {
        $count = $this->mongoDb->selectCollection('presentation')->find(array('members'=>$id))->count();
        if ($count != 0) {
            return false;
        } else {
            return true;
        }
    }
}
