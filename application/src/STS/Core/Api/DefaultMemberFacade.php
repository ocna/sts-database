<?php
namespace STS\Core\Api;
use STS\Core\Member\MemberDtoAssembler;
use STS\Domain\School\Specification\MemberSchoolSpecification;
use STS\Domain\Member\Specification\MemberByMemberAreaSpecification;
use STS\Core\Member\MemberDto;
use STS\Core\Api\MemberFacade;
use STS\Core\Member\MongoMemberRepository;

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
    public static function getDefaultInstance($config)
    {
        $mongoConfig = $config->modules->default->db->mongodb;
        $auth = $mongoConfig->username ? $mongoConfig->username . ':' . $mongoConfig->password . '@' : '';
        $mongo = new \Mongo(
                        'mongodb://' . $auth . $mongoConfig->host . ':' . $mongoConfig->port . '/'
                                        . $mongoConfig->dbname);
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
}
