<?php
namespace STS\Domain\Location\Specification;

use STS\Domain\Member;
use STS\Domain\Location\Locatable;

class MemberLocationSpecification
{
    private $member;

    public function __construct($member)
    {
        if (!$member instanceof Member) {
            throw new \InvalidArgumentException('Instance of Member required.');
        }
        $this->member = $member;
    }

    public function isSatisfiedBy(Locatable $location)
    {
        if (in_array($location->getArea(), $this->member->getAllAssociatedAreas())) {
            return true;
        } else {
            return false;
        }
    }
}
