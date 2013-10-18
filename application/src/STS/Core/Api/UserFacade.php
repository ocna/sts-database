<?php
namespace STS\Core\Api;
interface UserFacade
{
    /**
     * @param $id
     * @return \STS\Core\User\UserDto
     */
    public function findUserById($id);

    /**
     * @param $email
     * @return \STS\Core\User\UserDto
     */
    public function findUserByEmail($email);

    /**
     * @param $memberId
     * @return \STS\Core\User\UserDto
     */
    public function getUserByMemberId($memberId);

    /**
     * createUser
     *
     * $init_password will salt and hash a plain text password.
     *
     * @param $username
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $password
     * @param $role
     * @param $associatedMemberId
     * @param bool $init_password
     * @return mixed
     */
    public function createUser($username, $firstName, $lastName, $email, $password, $role, $associatedMemberId, $init_password = false, $salt = null);

    public function updateUser($username, $firstName, $lastName, $email, $password, $role, $associatedMemberId);

    public function deleteUser($id);
}
