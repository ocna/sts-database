<?php
namespace STS\Domain\School\Specification;
use STS\Domain\Member;
class MemberSchoolSpecification {
    public function __construct($member){
        if(! $member instanceof Member){
            throw new \InvalidArgumentException('Instance of Member required.');
        }
    }
}