<?php
namespace App\Services;

use App\Interfaces\UserInterface;
use Illuminate\Support\Facades\Auth;

class UserService
{
    protected $user;
    
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }
    
    public function authUser($credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = $this->user->authUser($credentials);
            $token = $user->createToken('user-token-' . $user->id)->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token
            ];
            return $response;
        } else {
            return response()->json(['message' => 'Invalid email or password'], 401);
        };
    }

    public function registerUser($fields)
    {
        $user = $this->user->registerUser($fields);
        if ($user) {
            $token = $user->createToken('user-token-' . $user->id)->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token
            ];
            return $response;
        } else {
            return response()->json(['message' => 'Invalid user data'], 400);
        };
    }

    public function getUserProfile($id)
    {
        $user = $this->user->getUserProfile($id);
        if ($user) {
            return $user;
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function updateUserProfile($id, $newDetails)
    {
        $user = $this->user->updateUserProfile($id, $newDetails);
        if ($user) {
            $token = $user->createToken('user-token-' . $user->id)->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token
            ];
            return $response;
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

}
