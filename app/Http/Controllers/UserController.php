<?php

namespace App\Http\Controllers;

use App\DTOs\UserLoginDTO;
use App\DTOs\UserRegisterDTO;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Libraries\General;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    public function register(RegisterRequest $request)
    {
        $userData = UserRegisterDTO::fromRequest($request->validated())->toArray();

        $user = $this->userService->registerUser($userData);

        $token = $this->userService->createAccessToken($user);

        return General::response(
            true,
            ['user' => UserResource::make($user), 'token' => $token],
            'User created successfully'
        );
    }

    public function login(LoginRequest $request)
    {
        $userData = UserLoginDTO::fromRequest($request->validated())->toArray();
        $user = $this->userService->getUserByEmail($userData['email']);

        if (!$this->userService->checkUserPassword($user, $userData['password'])) {
            return General::response('false', null, 'Password is wrong', true, 400);
        }

        $token = $this->userService->createAccessToken($user);

        return General::response(
            true,
            ['user' => UserResource::make($user), 'token' => $token],
            'User logged in successfully'
        );
    }
}
