<?php

namespace App\Http\Controllers\Api\Agent;

use App\Enums\AgentConfirmMethod;
use App\Events\NewAgentSignUpEvent;
use App\Http\Controllers\Controller;
use App\Jobs\SendSmsJob;
use App\Mail\AgentAccountCreated;
use App\Mail\AgentEmailValidationOtp;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class SignupController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'email' => 'required_if:phone,null|email|unique:agents,email',
            'username' => 'required|string|without_spaces|unique:agents,username',
            'phone' => 'required_if:email,null|phone|unique:agents,phone',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ]
        ]);

        $agent = Agent::create([
            ...$data,
            'password' => \Hash::make($data['password']),
        ]);
        NewAgentSignUpEvent::dispatch($agent, \Arr::has($data, 'email') ? AgentConfirmMethod::EMAIL : AgentConfirmMethod::PHONE);
        return response()->json([
            'message' => "Account created successfully",
            'token' => $agent->createToken('login')->plainTextToken
        ]);
    }

    public function resendEmailValidationOtp(Request $request)
    {
        $agent = $request->user();
        $emailCode = \Cache::remember('email_code:' .  md5($agent->id), now()->addMinutes(10), function () {
            return rand(000000, 999999);
        });

        \Mail::to($agent)->send(new AgentEmailValidationOtp($agent, $emailCode));

        return response()->json(['message' => "Validation email sent"]);
    }

    public function validateEmail(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|numeric|digits:6'
        ]);

        $otp = \Cache::get('email_code:' . md5(\Auth::user()->id));

        if (is_null($otp)) return response()->json([
            'message' => "OTP expired please. Please, request a new code"
        ], 400);

        if ($otp !== $data['code']) return response()->json([
            'message' => "Invalid code. Please, confirm and try again."
        ]);

        $agent = $request->user();
        $agent->email_verified_at = now();
        $agent->save();

        return response()->json(['message' => "Successful"]);
    }
}
