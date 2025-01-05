<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function register(Request $request){
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create a personal access token
        $token = $user->createToken('auth_token')->accessToken;


        return response()->json([
            'status' => 'success',
            'token' => $token,
        ], 201);
    }


    public function login(Request $request){

        $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);
        $user=User::where('email',$request->email)->first();

        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json([
                'result'=>"fail incorrect password"
            ]);

        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'success'=>"correct password",
            'token'=>$token
        ]);


    }
    public function getuser(){
        $user=User::find(8);
        return response()->json([
            'result'=>'success',
            'info'=>$user
        ]);
    }
}

