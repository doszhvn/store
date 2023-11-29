<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(UserRequest $request)
    {
        $data = $request->validated();
        $data['password']= Hash::make($data['password']);
        $data['role'] = 'user';
        $user = User::create($data);
        if($user){
            return response()->json([
                'message' => 'User registered successfully',
            ], 200);
        }
    }
}
