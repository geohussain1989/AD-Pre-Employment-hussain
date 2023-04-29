<?php
namespace App\Interfaces;

use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function register(RegisterUserRequest $request);
    public function getProfile(Request $request);
}
