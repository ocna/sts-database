<?php
namespace STS\Domain\Member;
interface MemberRepository
{
    public function searchByName($searchString);
    public function load($id);
    public function find();
    public function save($member);
}
