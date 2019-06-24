<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Parser;
use DB;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

    public function login(Request $request) {
        try {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                if (Auth::attempt(array('email' => $request->email, 'password' => $request->password))) {
                    $user = Auth::user();
                    $id = $user->id;

                    $data['token'] = $user->createToken('MyApp')->accessToken;
                    $data['name'] = $user->name;
                    return response()->json($data, 200);
                } else {
                    $data['status'] = 0;
                    $data['message'] = "Invalid Credentials";
                    return response()->json($data, 422);
                }
            } else {
                $data['status'] = 0;
                $data['message'] = 'Invalid Credentials';
                return response()->json($data, 422);
            }
        } catch (\Exception $e) {
            $data['message'] = 'Operation Failed';
            return response()->json($data, 422);
        }
    }

    public function logoutApi(Request $request) {
        try {
            $value = $request->bearerToken();
            $id = (new Parser())->parse($value)->getHeader('jti');

            DB::table('oauth_access_tokens')
                    ->where('id', $id)
                    ->update([
                        'revoked' => true
            ]);

           
           
            $data['status'] = 1;
            $data['message'] = "Successfully logged out";
            return response()->json($data, 200);
        } catch (\Exception $e) {
            $data['status'] = 0;
            $data['message'] = 'Operation Failed';
            return response()->json($data, 422);
        }
    }

    public function signup(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        return response()->json([
                    'message' => 'Successfully created user!'
                        ], 201);
    }


}
