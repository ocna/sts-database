<?php
namespace STS\Core\Api;
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
    public function searchForMembersByName($searchString)
    {
        $members = $this->memberRepository->searchByName($searchString);
        $memberDtos = array();
        foreach ($members as $member) {
            $memberDtos[] = new MemberDto($member->getId(), $member->getLegacyId(), $member->getFirstName(),
                            $member->getLastName());
        }
        return $memberDtos;
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
}
