<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterUserRequest;

class AuthController extends Controller
{
    public function registerUser(RegisterUserRequest $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();
            $input['password'] = Hash::make($request->input('password'));
            $user = User::create($input);

            $tokenObj = $user->createToken('news_blog', [], now()->addDays(365));
            $token = $tokenObj->plainTextToken;

            DB::commit();
            // event(new Registered($user));

            return response()->json(
                [
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'token_expiration' => $tokenObj->accessToken->expires_at,
                    'user' => new UserResource($user)
                ],
                200
            );
        } catch (\Exception $exception) {
            DB::rollback();
            Log::error(['message' => $exception->getMessage()]);

            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        if (auth('web')->attempt($request->validated())) {
            $user = User::find(auth('web')->user()->id);

            // if (!$user->email_verified_at) {
            //     event(new Registered($user));
            //     return response()->json([
            //         'message' => 'User not verified. Check email for verification link'
            //     ]);
            // }

            $tokenObj = $user->createToken('news_blog', [], now()->addDays(365));

            return response()->json([
                'token' => $tokenObj->plainTextToken,
                'token_type'   => 'Bearer',
                'token_expiration'  => $tokenObj->accessToken->expires_at,
                'email_verified' => (bool) $user->email_verified_at,
                'user' => new UserResource($user)
            ]);
        } else {
            return response()->json(['error' => 'Invalid login credentials'], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully logout'
            ]
        ]);
    }
}