<?php

namespace App\Http\Controllers\Api\User;
//require_once '/path/to/vendor/autoload.php';

use App\Http\Requests\User\OtpVerifyRequest;
use Twilio\Rest\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\User\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
        $user = User::VerifiedUser()->where('phone', $request->phone)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'phone' => ['The provided credentials are incorrect.'],
            ]);
        }
        return $this->makeToken($user);

    }
    public function register(RegisterRequest $request){

        try{
            $user=User::create($request->validated());

        $data=twilio_env();
        $res=$data->verifications->create("+88".$user->phone, "sms");
        return send_ms('OTP send success',$res->status,200);
        }catch (\Exception $e){
            return send_ms($e->getMessage(),false,$e->getCode());
        }
//        print($verification->status);
//        return $this->makeToken($user);
    }
    public function verifyOtp(OtpVerifyRequest $request){

        try {
            $data=twilio_env();
            $res=$data->verificationChecks->create([
                    "to" => "+88".$request->phone,
                    "code" => $request->otp_code,
                ]
            );
            if ($res->status == 'approved'){
                $user = User::where('phone', $request->phone)->first();
                $user->isVerified = 1;
                $user->save();
                return $this->makeToken($user);
            }
        }catch (\Exception $e){
            return send_ms($e->getMessage(),false,$e->getCode());
        }


//        return send_ms('OTP send success',$res->status,200);
//        print($verification_check->status);
    }
    public function otpResend(Request $request){
        try{
            $user=User::where('phone', $request->phone)->first();

            $data=twilio_env();
            $res=$data->verifications->create("+88".$user->phone, "sms");
            return send_ms('OTP send success',$res->status,200);
        }catch (\Exception $e){
            return send_ms($e->getMessage(),false,$e->getCode());
        }

    }
    public  function makeToken($user){
        try{
        $token= $user->createToken('user-token')->plainTextToken;
        return (new AuthResource($user))
            ->additional(['meta' => [
                'token' => $token,
                'token_type' => 'Bearer',
            ]]);
        }catch (\Exception $e){
            return send_ms($e->getMessage(),false,$e->getCode());
        }
    }
    public function logout(Request $request){
        try{
        $request->user()->tokens()->delete();
        return send_ms('User Logout',true,200);
        }catch (\Exception $e){
            return send_ms($e->getMessage(),false,$e->getCode());
        }

    }
    public function user(Request $request){
        return AuthResource::make($request->user());

    }
}
