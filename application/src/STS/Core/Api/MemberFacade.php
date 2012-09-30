<?php
namespace STS\Core\Api;
interface MemberFacade
{
    public function getMemberById($id);
    public function getAllMembers();
    public function searchForMembersByNameWithSpec($searchString, $spec);
    public function getMemberByMemberAreaSpecForId($id);
    public function getMemberSchoolSpecForId($id);
    public function saveMember($firstName, $lastName, $type, $status, $notes, $presentsFor, $facilitatesFor, $coordinatesFor, $userId, $addressLineOne, $addressLineTwo, $city, $state,
                    $zip);
}
