<?php
namespace STS\Domain\School\Specification;
use STS\Domain\Member;
use STS\Domain\School;

class MemberSchoolSpecification
{

    private $member;
    public function __construct($member)
    {
        if (!$member instanceof Member) {
            throw new \InvalidArgumentException('Instance of Member required.');
        }
        $this->member = $member;
    }
    public function isSatisfiedBy(School $school)
    {
        if (in_array($school->getArea(), $this->member->getAllAssociatedAreas())) {
            return true;
        } else {
            return false;
        }
    }
}
