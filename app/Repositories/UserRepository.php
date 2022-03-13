<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\UserInterface;

class UserRepository implements UserInterface
{
    protected $user;
    
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function registerUser(array $fields)
    {
        $createdUser = $this->user::create([
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);
        $user = $this->user::where('email', $createdUser->email)->first();
        return $user;
    }

    public function authUser(array $credentials)
    {
        $user = $this->user::where('email', $credentials['email'])->first();
        return $user;
    }

    // public function logoutUser($user)
    // {
    //     return $user;
    // }

    public function getUserProfile($id)
    {
        $user = $this->user::findOrFail($id);
        return $user;
    }

    public function updateUserProfile($id, array $newDetails)
    {
        $user = $this->user::findOrFail($id);
        $user->fill([
                'first_name' => $newDetails['first_name'] ?? $user->first_name,
                'last_name' => $newDetails['last_name'] ?? $user->last_name,
                'email' => $newDetails['email'] ?? $user->email,
                'password' => bcrypt($newDetails['password']) ?? $user->password
        ]);
        $user->save();
        return $user;
    }
}
