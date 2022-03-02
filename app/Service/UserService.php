<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{

    public function __construct() {

    }

    public function create($userInfo)
    {
        $user = User::create([
            "phone" => $userInfo->phone,
            "password" =>  Hash::make($userInfo->password),
        ]);
        return $user->id;
    }

    public function createWithoutPassword($userInfo)
    {
        $user = User::create([
            "phone" => $userInfo->phone,
            "password" =>  $userInfo->password,
        ]);
        return $user;
    }

    public function countByPhone($phone)
    {
        return User::where("phone",$phone)->count();
    }
}
