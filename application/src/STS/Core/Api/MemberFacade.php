<?php
namespace STS\Core\Api;
interface MemberFacade
{
    public function getMemberById($id);
    public function getAllMembers();
    public function searchForMembersByNameWithSpec($searchString, $spec);
    public function getMemberByMemberAreaSpecForId($id);
    public function getMemberSchoolSpecForId($id);
}
