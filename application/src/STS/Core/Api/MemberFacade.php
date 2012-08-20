<?php
namespace STS\Core\Api;
interface MemberFacade
{
    public function searchForMembersByNameWithSpec($searchString, $spec);
    public function getMemberByMemberAreaSpecForId($id);
    public function getMemberSchoolSpecForId($id);
}
