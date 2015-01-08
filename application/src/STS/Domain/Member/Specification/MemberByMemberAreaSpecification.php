<?php
namespace STS\Domain\Member\Specification;

use STS\Domain\Member;

class MemberByMemberAreaSpecification
{
    private $member;

    public function __construct($member)
    {
        if (!$member instanceof Member) {
            throw new \InvalidArgumentException('Instance of Member required.');
        }
        $this->member = $member;
    }

    /**
     * @param Member $member
     *
     * @return bool
     */
    public function isSatisfiedBy($member)
    {
        foreach ($member->getAllAssociatedAreas() as $area) {
            if (in_array($area, $this->member->getAllAssociatedAreas())) {
                return true;
            }
        }
        return false;
    }
}
