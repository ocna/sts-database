<?php
namespace STS\Core\User;

use STS\Domain\User;

class UserDTOAssembler
{
    public static function toDTO($user)
    {
        // TODO use a typehint
        if (!($user instanceof User)) {
            throw new \InvalidArgumentException(
                'Instance of \STS\Domain\User\User not provided. Other value is given.'
            );
        }

        $userDTOBuilder = new UserDTOBuilder();
        $userDTOBuilder->withId($user->getId());
        $userDTOBuilder->withEmail($user->getEmail());
        $userDTOBuilder->withFirstName($user->getFirstName());
        $userDTOBuilder->withLastName($user->getLastName());
        $userDTOBuilder->withRole($user->getRole());
        $userDTOBuilder->withLegacyId($user->getLegacyId());
        $userDTOBuilder->withAssociatedMemberId($user->getAssociatedMemberId());
        $userDTOBuilder->withPassword($user->getPassword());
        $userDTOBuilder->withSalt($user->getSalt());

        return $userDTOBuilder->build();
    }
}
