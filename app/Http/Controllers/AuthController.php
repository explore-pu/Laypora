<?php


namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = $request->authenticate();

        $data = [
            'name' => $user->name,
            'avatar' => $user->avatar,
        ];

        return response()
            ->json($data)
            ->header('Authorization', 'Bearer ' . $user->api_token);
    }
}
