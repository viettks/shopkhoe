<?php

namespace App\Service;

use App\Models\SMSConfig;
use Illuminate\Support\Facades\Http;

use function PHPUnit\Framework\isNull;

class SMSSendService
{

    protected $content;
    protected $config;
    public const SEND_SUCCESS = "100";

    public function __construct() {
        $this->content = "là mã xác thực của bạn.";

        $this->config = SMSConfig::all()->first();
    }

    public function sendOTP($phone, $otp, $content = NULL)
    {
        $contentBody = "";
        if(isNull($content)){
           $contentBody = $otp ." " .$this->content;
        }else{
            $contentBody = $otp ." " .$content;
        }
        $smsConfig = SMSConfig::all()->first();
        $response = Http::acceptJson()->post('http://rest.esms.vn/MainService.svc/json/GetBalance_json/',[
            "ApiKey" => $smsConfig->api_key,
            "SecretKey"=> $smsConfig->secret_key,
        ]);
        
        $jsonResponse = json_decode($response);
        return $jsonResponse;
    }

    public function getAPIInfo()
    {
        return $this->config;
    }

    public function getSMSInfo()
    {
        return $this->config;
    }
}
