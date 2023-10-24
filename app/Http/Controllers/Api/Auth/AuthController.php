<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
