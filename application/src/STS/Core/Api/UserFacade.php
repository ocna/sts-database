<?php
namespace STS\Core\Api;
interface UserFacade
{
    public function findUserById($id);

    public function findUserByEmail($email);

    public function getUserByMemberId($memberId);

    public function createUser($username, $firstName, $lastName, $email, $password, $role, $associatedMemberId);

    public function updateUser($username, $firstName, $lastName, $email, $password, $role, $associatedMemberId);
}
