<?php
namespace STS\Core\Member;
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
    private function mapData($memberData)
    {
        $member = new Member();
        $member->setId($memberData['_id']->__toString())->setLegacyId($memberData['legacyid'])->setFirstName($memberData['fname'])
            ->setLastName($memberData['lname']);
        return $member;
    }
}
