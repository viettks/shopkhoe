<?php

namespace App\Service;

use App\Models\Register as ModelsRegister;
use App\Models\SMSConfig;
use App\Repository\RegisterRepository;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    protected $registerRepository;

    protected $smsService;

    public function __construct() {
        $this->registerRepository = new RegisterRepository();
        $this->smsService = new SMSSendService();
    }

    public function create($variable)
    {
        $fouthRegist = rand(0000,9999);
        $secrecKey =bin2hex(random_bytes(20));

        $variable->otp = $fouthRegist;
        $variable->secret_key = $secrecKey;

        $sendCode = $this->smsService->sendOTP($variable->phone,$variable->otp);
        if($sendCode->CodeResponse == SMSSendService::SEND_SUCCESS){

            $variable->password = Hash::make($variable->password);
            $regist = $this->registerRepository->create($variable);
            return $regist;
        }else{
            return null;
        }
    }

    public function getInfoByOTP($variable)
    {
        return $this->registerRepository->getInfoByOTP($variable);
    }

    public function disabledRegistPhone($phone)
    {
        ModelsRegister::where("phone",$phone)->update(["enable"=>0]);
    }
}
