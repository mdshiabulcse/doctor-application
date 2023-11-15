<?php

namespace App\Http\Controllers\Api\User;
//require_once '/path/to/vendor/autoload.php';

use App\Http\Requests\User\OtpVerifyRequest;
use App\Models\UserAccessLog;
use App\Traits\ApiStatusTrait;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\User\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Jenssegers\Agent\Facades\Agent;

class AuthController extends Controller
{
    use ApiStatusTrait;

    public function login(LoginRequest $request)
    {

//        DB::beginTransaction();
//        try {
            $user = User::where('phone', $request->phone)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'phone' => ['The provided credentials are incorrect.'],
                ]);
            }
            $browserName = Agent::browser();
            $browserVersion = Agent::version($browserName);
            $deviceInfo = Agent::device();
            $osPlatform = Agent::platform();

            $userLog = new UserAccessLog();
            $userLog->ip_address = request()->ip();
            $userLog->browser_name = $browserName;
            $userLog->browser_version = $browserVersion;
            $userLog->device_info = $deviceInfo;
            $userLog->os_platform = $osPlatform;
            $userLog->user_id = $user->id;
            $userLog->login_time = now();
            $userLog->save();
            $userAccess=$userLog->id;
//            DB::commit();
//        } catch (\Exception $e) {
//            DB::rollBack();
//            $response['errors']=$e->getMessage().$e->getLine()  ;
//            return $this->failureApiResponse($response);
//        }
        return $this->makeToken($user,$userAccess);

    }

    public function register(RegisterRequest $request)
    {

        try {
            $user = User::create($request->validated());

            $data = twilio_env();
            $res = $data->verifications->create("+88" . $user->phone, "sms");
            return send_ms('OTP send success', $res->status, 200);
        } catch (\Exception $e) {
            return send_ms($e->getMessage(), false, $e->getCode());
        }
//        print($verification->status);
//        return $this->makeToken($user);
    }

    public function verifyOtp(OtpVerifyRequest $request)
    {

        try {
            $data = twilio_env();
            $res = $data->verificationChecks->create([
                    "to" => "+88" . $request->phone,
                    "code" => $request->otp_code,
                ]
            );
            if ($res->status == 'approved') {
                $user = User::where('phone', $request->phone)->first();
                $user->isVerified = 1;
                $user->save();
                return $this->makeToken($user);
            }
        } catch (\Exception $e) {
            return send_ms($e->getMessage(), false, $e->getCode());
        }


    }

    public function otpResend(Request $request)
    {
        try {
            $user = User::where('phone', $request->phone)->first();

            $data = twilio_env();
            $res = $data->verifications->create("+88" . $user->phone, "sms");
            return send_ms('OTP send success', $res->status, 200);
        } catch (\Exception $e) {
            return send_ms($e->getMessage(), false, $e->getCode());
        }

    }

    public function makeToken($user,$userAccess)
    {
        try {
            @$userAccessLog=UserAccessLog::where('id',$userAccess)->first();
            $token = $user->createToken('user-token')->plainTextToken;
            return (new AuthResource($user))
                ->additional(['meta' => [
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'user_access_log'=>@$userAccessLog,
                ]]);
        } catch (\Exception $e) {
            return send_ms($e->getMessage(), false, $e->getCode());
        }
    }

    public function logout(Request $request)
    {
        try {
            $userAccessLog=UserAccessLog::where('id',$request->id)->first();
            $userAccessLog->logout_time = now();
            $userAccessLog->save();
            $request->user()->tokens()->delete();
            return send_ms('User Logout', true, 200);
        } catch (\Exception $e) {
            return send_ms($e->getMessage(), false, $e->getCode());
        }

    }

    public function user(Request $request)
    {
        return AuthResource::make($request->user());
    }
}
