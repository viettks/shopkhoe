<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Service\RegisterService;
use App\Service\SMSSendService;
use App\Service\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

use function PHPUnit\Framework\isNull;

class AuthController extends Controller
{

    protected $registerService;

    protected $userService;

    const SMS_SEND_OK = 100;
        /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->registerService = new RegisterService();
        $this->userService = new UserService();
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_name' => 'required',
                'password' => 'required']);
    
            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }
            $credentials = array(
                "phone" => $request->user_name,
                "password" => $request->password,
            );
            
            if (! $token = JWTAuth::attempt(["phone"=>$request->user_name,"password"=>$request->password])) {
                return response()->json(['error' => 'Tài khoản hoặc mật khẩu không đúng.','code'=>404], 200);
            }
    
            return $this->createNewToken($token)->withCookie(cookie('token', $token, 120));
        } catch (Exception $exception) {
            echo $exception->getMessage();
            return response()->json([
                'message' =>  "Some error",
            ], 500);
        }
    }

    public function register(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'phone' => 'required|min:10',
                'password' => 'required|min:6', 
                ]);
    
            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }
            $register = (object)array();
            $register->phone = $request->phone;
            $register->password = $request->password;

            $smsService = new SMSSendService();

            $smsConfig = $smsService->getAPIInfo();
            if($smsConfig->enable == 1){
                $regist =  $this->registerService->create($register);
                if(isset($regist)){
                    return response()->json([
                        'message' =>  "Done!",
                        'url' => 'register/' .$regist->secret_key,
                    ], 200);
                }

            }else{
                $userService = new UserService();
                $userService->create($register);

                return response()->json([
                    'message' =>  "Done!",
                    'url' => 'login'
                ], 200);
            }

            return response()->json([
                'message' =>  "Some error",
            ], 500);
        } catch (Exception $exception) {

            return response()->json([
                'message' =>  "Some error",
            ], 500);
        }
    }

    public function confirmOTP(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'otp' => 'required',
                'secret_key' => 'required', 
                ]);
    
            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }
            $info = (object) array();
            $info->otp = $request->otp;
            $info->secret_key = $request->secret_key;
            $regist = $this->registerService->getInfoByOTP($info);
            

            if(isset($regist)){
                $userCnt = $this->userService->countByPhone($regist->phone);
                if($userCnt > 0){
                    return response()->json([
                        'message' =>  "Số điện thoại đã được đăng ký trên hệ thống!",
                        'url' => 'login',
                        'code' => 400,
                    ], 201);
                }else{
                    $user = $this->userService->createWithoutPassword($regist);
                    $this->registerService->disabledRegistPhone($regist->phone);
                    if(isset($user)){
    
                        return response()->json([
                            'message' =>  "Tạo thành công!",
                            'url' => 'login',
                            'code' => 200,
                        ], 201);
                    }
                }


            }else{
                return response()->json([
                    'message' =>  "Sai OTP",
                    'url' => "",
                    'code' => 404,
                ], 200);
            }
            return response()->json([
                'message' =>  "Đã xảy ra lỗi",
                'url' => "",
                'code' => 000,
            ], 200);

        } catch (Exception $exception) {
            echo $exception;
            return response()->json([
                'message' =>  "Some error",
            ], 500);
        }
    }

    protected function createNewToken($token)
    {
        try{
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'code' => 200,
                'expires_in' => 100 * 600
            ]);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
