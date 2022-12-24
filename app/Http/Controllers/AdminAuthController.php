<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login(request $request)
    {
        $var = $request->validate([
            'admin_id' => 'required|string',
            'password' => 'required|string'
        ]);

        // check if exist
        $admin = Admin::where('admin_id', $var['admin_id'])->first();
        if(!$admin || !Hash::check($var['password'], $admin->password)):
            return response(
                ['message' => 'ID or password is wrong'],
                401
            );
        endif;

        // $token = $admin->createToken('studnet_token')->plainTextToken;

        $response = [
            'admin_id' => $admin['admin_id'],
            'admin_name' => $admin['admin_name'],
            'email' => $admin['email'],
            'phone_number' => $admin['phone_number']
        ];

        return response($response, 200);
    }

    public function logout(request $request)
    {
        // auth()->admin()->tokens()->delete();
        return [
            'message' => 'logged out'
        ];
    }
}
