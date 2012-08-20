<?php
namespace STS\Core\Api;
interface MemberFacade
{
    public function searchForMembersByNameWithSpec($searchString, $spec);
}
