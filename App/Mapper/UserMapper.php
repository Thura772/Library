<?php

namespace App\Mapper;

use App\Model\User;
use App\DTO\UserDTO;

class UserMapper
{
    public static function toDTO(User $user): UserDTO
    {
        return new UserDTO(
            $user->getId(),   // now nullable safe
            $user->getName(),
            $user->getEmail(),
            $user->getRole()
        );
    }
}