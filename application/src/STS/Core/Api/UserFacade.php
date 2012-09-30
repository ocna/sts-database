<?php
namespace STS\Core\Api;
interface UserFacade
{
    public function findUserById($id);

    public function findUserByEmail($email);
}
