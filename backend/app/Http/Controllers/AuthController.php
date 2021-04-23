<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ( !$user || !Hash::check($request->password, $user->password)){
            return $this->sendResponse(array(
                'status' => 0,
                'errors' => 'Credentials doesn\'t match'
            ));
        }


        return $this->sendResponse(array(
            'status' => 1,
            'token' => $this->createToken($user)
        ));

    }




    public function register(RegisterRequest $request, User $user)
    {
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return $this->sendResponse(array(
            'status' => 1,
            'token' => $this->createToken($user),
        ));


    }



    public function logout()
    {
        auth('sanctum')->user()
            ->currentAccessToken()
            ->delete();

        return $this->sendResponse(array(
            'status' => 1
        ));
    }



    public function createToken($user)
    {
        $token = $user->createToken('user-auth');

        return $token->plainTextToken;
    }


}
