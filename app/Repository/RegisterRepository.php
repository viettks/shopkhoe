<?php

namespace App\Repository;

use App\Models\Register;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;

class RegisterRepository
{

    public function create($variable)
    {
        $register = new Register((array)$variable);
        $register->save();
        return $register;
    }

    public function getInfoByOTP($variable)
    {
        $register = Register::where([
           "otp" => $variable->otp,
           "secret_key" =>  $variable->secret_key,
        ])->first(); 

        return $register;
    }
}
