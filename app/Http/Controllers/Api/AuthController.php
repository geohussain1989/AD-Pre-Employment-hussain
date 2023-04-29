<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    protected UserRepositoryInterface $userRepository ;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepository = $userRepositoryInterface;
    }
    /**
     * User login API method
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginUserRequest $request)
    {

        $credentials = $request->only('email', 'password');
        if(!Auth::attempt($credentials)){
            return $this->sendResponse(Response::HTTP_UNAUTHORIZED, [] , __('message.user_invalid_credentials'));
        }
        $user = Auth::user();

        if($user->verified == 0 ){
            return $this->sendResponse(Response::HTTP_UNAUTHORIZED, [] , __('message.user_not_verified'));
        }
        $user->access_token= $user->createToken('accessToken')->accessToken;
        $user = UserResource::make($user);
            
        return $this->sendResponse(Response::HTTP_OK, $user, __('message.user_created'));
    }

    public function register(RegisterUserRequest $request)
    {

        $user = DB::transaction(function() use ($request) {
            $user = $this->userRepository->register($request);
            $user->interests()->attach($request->interests);
            return UserResource::make($user);
        });
        
        return $this->sendResponse(Response::HTTP_CREATED, $user, __('message.user_created'));

    }
}
