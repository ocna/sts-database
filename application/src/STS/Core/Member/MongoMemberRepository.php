<?php
namespace STS\Core\Member;
use STS\Domain\Location\Area;
use STS\Domain\Location\Region;
use STS\Domain\Location\Address;
use STS\Domain\Member;
use STS\Domain\Member\MemberRepository;
use STS\Core\Location\MongoAreaRepository;
use STS\Core\User\MongoUserRepository;
use STS\Domain\Member\Diagnosis;
use STS\Domain\Member\PhoneNumber;

class MongoMemberRepository implements MemberRepository
{

    private $mongoDb;
    public function __construct($mongoDb)
    {
        $this->mongoDb = $mongoDb;
    }
    public function find()
    {
        $memberData = $this->mongoDb->selectCollection('member')->find()->sort(array(
                'lname' => 1
            ));
        return $this->mapMultiple($memberData);
    }
    public function save($member)
    {
        if (!$member instanceof Member) {
            throw new \InvalidArgumentException('Instance of Member expected.');
        }
        $array = $member->toMongoArray();

        $id = array_shift($array);
        $array['dateCreated'] = new \MongoDate();
        $results = $this->mongoDb->selectCollection('member')
            ->update(array(
                '_id' => new \MongoId($id)
            ), $array, array(
                'upsert' => 1, 'safe' => 1
            ));
        if (array_key_exists('upserted', $results)) {
            $member->setId($results['upserted']->__toString());
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
    public function load($id)
    {
        $mongoId = new \MongoId($id);
        $memberData = $this->mongoDb->selectCollection('member')->findOne(array(
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
        if (array_key_exists('date_trained', $memberData)) {
            $member->setDateTrained(date('Y-M-d h:i:s', $memberData['date_trained']->sec));
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
            //var_dump($memberData);
            if (array_key_exists('user_id', $memberData) && isset($memberData['user_id'])) {
                try{


                $userRepository = new MongoUserRepository($this->mongoDb);
                $user = $userRepository->load($memberData['user_id']);
                $member->setEmail($user->getEmail());
            } catch (Exception $e){
                var_dump($memberData);
            }
            }
        }
        if (array_key_exists('diagnosis', $memberData)) {
            $member->setDiagnosis(
                new Diagnosis(
                    date('Y-M-d h:i:s', $memberData['diagnosis']['date']->sec),
                    $memberData['diagnosis']['stage']
                )
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
        return $member;
    }
}
