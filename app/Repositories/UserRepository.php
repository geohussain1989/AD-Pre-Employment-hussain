<?php

namespace App\Repositories;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserRepository implements UserRepositoryInterface
{
    public function register(RegisterUserRequest $request)
    {
        return User::Register($request);
    }

    public function getProfile(Request $request)
    {
        return User::getProfile($request);
    }
}
