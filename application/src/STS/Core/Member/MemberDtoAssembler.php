<?php
namespace STS\Core\Member;
use STS\Domain\Member;

class MemberDtoAssembler
{
    public static function toDTO($member)
    {
        if (!($member instanceof Member)) {
            throw new \InvalidArgumentException('Instance of \STS\Domain\Member not provided.');
        }
        $id = $member->getId();
        $legacyId = $member->getLegacyId();
        $firstName = $member->getFirstName();
        $lastName = $member->getLastName();
        $deceased = $member->isDeceased();
        $type = $member->getType();
        $notes = $member->getNotes();
        $associatedUserId = $member->getAssociatedUserId();
        if ($address = $member->getAddress()) {
            $addressLineOne = $address->getLineOne();
            $addressLineTwo = $address->getLineTwo();
            $addressCity = $address->getCity();
            $addressState = $address->getState();
            $addressZip = $address->getZip();
        } else {
            $addressLineOne = null;
            $addressLineTwo = null;
            $addressCity = null;
            $addressState = null;
            $addressZip = null;
        }
        return new MemberDto($id, $legacyId, $firstName, $lastName, $type, $notes, $deceased, $addressLineOne,
                        $addressLineTwo, $addressCity, $addressState, $addressZip, $associatedUserId);
    }
}
