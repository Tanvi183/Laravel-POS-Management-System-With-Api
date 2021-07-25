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

    public function getusers()
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    public function storeuser(Request $request)
    {
        $data = $request->all();
        $validation = Validator::make($data,[
            'name' => 'required',
            'email' => 'email|required|unique:users,email',
            'password' => 'required|min:8|confirmed'
        ]);

        if ($validation->fails()) {
            return response()->json(['error'=>$validation->errors()], 403);
        }

        $data['password'] = Hash::make($request->password);
        $user = User::create($data);
        return response()->json($user, 201);
    }

    public function singleuser($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $validation = Validator::make($data,[
            'email'=> "email|unique:users,email,$id",
        ]);

        if ($validation->fails()) {
            return response()->json(['error'=>$validation->errors()], 403);
        }

        try {
            $user = User::findOrFail($id);
            $user->name  = $request->name;
            $user->email = $request->email;
            $user->save();
            return response()->json('Successfully Data Updated', 200);
        } catch (\Throwable $th) {
            return response()->json(['error'=>$th->errorINfo[2]], 403);
        }
    }

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json('Successfully Data Deleted', 200);
        } catch (\Throwable $th) {
            return response()->json(['error'=>$th->errorINfo[2]], 403);
        }
    }

}

