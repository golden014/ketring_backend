<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //login
    public function login(Request $request) {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        $login = $request->only('email', 'password');  
        
        //attempt utk login berdasarkan credential yg diberikan
        if(!Auth::attempt($login)) {
            //kalau invalid, return error message unauthorized
            return response(['error' => 'Invalid credential !'], 401);
        }
        /**
         * @var User $user
         */
         
         $user = Auth::user();
         
         $token = $user->createToken($user->name);
        // dd($user);
        // dd($user->tokens);

        return response([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'phone_number' => $user->phone_number,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'token' => $token,
        ], 200);
    }

    //logout
    public function logout(Request $request) {
        /**
         * @var User $user
         */

         $user = $request->user();

        //get currently logged in user
         $user = Auth::user(); 
        //  dd($user);
         $userToken = $user->tokens;
        //  $userToken->delet;
        foreach($userToken as $token){
            $token->delete();
        }
         return response(['message' => 'Logged out success'], 200);
    }

    //register
    public function register(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'phone_number' => 'required',
            'password' => 'required',
            'conf_password' => 'required'
        ]);

        if ($request->password != $request->conf_password) {
            return response(['error' => 'Password dont match'], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => 'Customer',
        ]);

        return response(['message' => 'Register user success'], 200);
    }
}
