<?php

namespace App\Interfaces;

interface UserInterface
{
    public function registerUser(array $fields);
    public function authUser(array $credentials);
    // public function logoutUser($user);
    public function getUserProfile($id);
    public function updateUserProfile($id, array $newDetails);
}
