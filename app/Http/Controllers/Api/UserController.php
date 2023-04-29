<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;

class UserController extends Controller
{

    protected UserRepositoryInterface $userRepository ;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepository = $userRepositoryInterface;
    }
    
    public function profile(Request $request)
    {

        $user = $this->userRepository->getProfile($request);
        $user = UserResource::make($user);
            
        return $this->sendResponse(Response::HTTP_OK, $user);
    }

   
}
