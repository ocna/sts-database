<?php
namespace STS\Domain\Member;
interface MemberRepository
{
    public function searchByName($searchString);
}
