<?php
namespace STS\Domain\User;
interface UserRepository
{
    public function load($id);

    public function find($criteria);
}
