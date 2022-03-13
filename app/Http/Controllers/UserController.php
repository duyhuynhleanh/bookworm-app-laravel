<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class UserController extends Controller
{
    protected $user;
    public function __construct(UserService $user)
    {
        $this->user = $user;
    }

    // @desc register a new user
    // @route POST /api/users
    // @access public
    public function registerUser(RegisterRequest $request)
    {
        $fields = $request->validated();
        return $this->user->registerUser($fields);
    }

    // @desc Auth user and get token
    // @route POST /api/users/login
    // @access public
    public function authUser(LoginRequest $request)
    {
        $credentials = $request->validated();
        return $this->user->authUser($credentials);
    }

    // @desc logout
    // @route POST /api/users/logout
    // @access private
    public function logoutUser(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return [
            'message' => 'Logged out'
        ];
    }

    // @desc get user profile
    // @route GET /api/users/profile
    // @access private
    public function getUserProfile()
    {
        $id = auth()->user()->id;
        return $this->user->getUserProfile($id);
    }

    // @desc    Update user profile
    // @route   PUT /api/users/profile
    // @access  Private
    public function updateUserProfile(Request $request)
    {
        $id = auth()->user()->id;
        $newDetails = $request->all();
        return $this->user->updateUserProfile($id, $newDetails);
    }
}
