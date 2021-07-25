<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function login(Request $request)
    {
        // return response()->json($request->all());
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            try {
                $user = Auth::user();
                $result['token'] = $user->createToken('APPLICATION')->accessToken;
                $result['name']  = $user->name;
                $result['eamil'] = $user->email;
                $result['id']    = $user->id;
                return response()->json(['result' => 'Successfully Login','data'=>$result], 200);
            } catch (\Exception $e) {
                // return response()->json(['error'=>$e->errorINfo[2]], 403);
                return $e;
            }
        }else {
            return response()->json(['error' => 'Unauthorized']);
        }
    }
}

