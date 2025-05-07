<?php

namespace App\Services;

use App\Models\User;
use App\DTOs\UserRegisterDTO;
use Illuminate\Support\Facades\Hash;
use App\Contracts\UserRepositoryInterface;

class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function registerUser(array $data)
    {
        return $this->userRepository->create($data);
    }

    public function createAccessToken(User $user)
    {
        return $user->createToken('access_token')->plainTextToken;
    }

    public function getUserByEmail(string $email)
    {
        return $this->userRepository->getByEmail($email);
    }

    public function checkUserPassword(User $user, $password) {
        if(!Hash::check($password, $user->password)) {
            return false;
        }
        return true;
    }
}
