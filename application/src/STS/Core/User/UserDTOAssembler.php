<?php
namespace STS\Core\User;
use STS\Domain\User;
use STS\Core\User\UserDTO;
use STS\Core\User\UserDTOBuilder;

class UserDTOAssembler
{
    public static function toDTO($user)
    {
        if (!($user instanceof User)) {
            throw new \InvalidArgumentException('Instance of \STS\Domain\User\User not provided. Other value is given.');
        }
        $userDTOBuilder = new UserDTOBuilder();
        $userDTOBuilder->withId($user->getId());
        $userDTOBuilder->withEmail($user->getEmail());
        $userDTOBuilder->withFirstName($user->getFirstName());
        $userDTOBuilder->withLastName($user->getLastName());
        $userDTOBuilder->withRole($user->getRole());
        $userDTOBuilder->withLegacyId($user->getLegacyId());
        return $userDTOBuilder->build();
    }
}
