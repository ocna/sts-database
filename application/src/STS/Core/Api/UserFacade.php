<?php
namespace STS\Core\Api;
interface UserFacade
{

    /**
     * Searches for users by an array of key value pairs on fields
     * 
     * @param unknown_type $params            
     */
    public function search($params);

    /**
     * given a email and pw, returns a user object or throws exceptions if
     * authentication fails
     * 
     * @param string $email            
     * @param string $password            
     */
    public function authenticate($email, $password);

    /**
     * saves a user object creating it if it is new, returns the updated user
     * object
     * 
     * @param STS\Domain\User\User $user            
     */
    public function save($user);

    /**
     * creates and returns a new user object given arguments
     * 
     * @param string $email            
     * @param string $password            
     * @param string $role            
     * @param int $memberId            
     */
    public function create($email, $password, $role, $memberId);

    /**
     * Resets the password for a user and returns the object, throws exceptions
     * 
     * @param string $email            
     * @param string $oldPassword            
     * @param string $newPassword            
     */
    public function resetPassword($email, $oldPassword, $newPassword);
}