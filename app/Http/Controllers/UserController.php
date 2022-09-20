<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    //
    public function register(UserRequest $request)
    {
        DB::beginTransaction();
        try {
            // Check if user existed OR not 
            $check = User::where('username', $request['username'])->first();

            if ($check)
                return response()->json(array('status' => false, 'message' => "The UserName  is Already Existed", 'statuscode' => 400), 400);


            $user = User::create([
                'username' => $request['username'],
                'password' => Hash::make($request['password']),
                'email' => $request['email'],
                'phone_number' => $request['phone_number'],
                'role_id' => $request['role_id'],

            ]);
            $user->save();

            DB::commit();
            return response()->json(array('status' => true, 'message' => "Thank You For Your Registration", 'statuscode' => 200), 200);
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }


    public function login(UserRequest $request)
    {
        try {
            $data = $request->only('username', 'password');
            $username = $data['username'];
            $password = $data['password'];

            $user = User::where('username', $username)->first();
            if (Hash::check($password, $user->password))
                return response()->json(array('status' => true, 'message' => "success", 'statuscode' => 200, 'user_data' => $user));

            return response()->json(array('status' => false, 'message' => "user name and password don't match", 'statuscode' => 400), 400);
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }


    public function checkRole($id)
    {

        $user = User::with('role')->where('id', $id)->first();
        if (empty($user)) {
            return response()->json(array('status' => false, 'message' => "No User Found", 'statuscode' => 400), 400);
        } elseif ($user->role->role_name == "Admin") {
            return response()->json(array('status' => true, 'message' => "Success ", 'statuscode' => 200));
        }
        return response()->json(array('status' => false, 'message' => "False You not Admin", 'statuscode' => 400), 400);
    }


    public function logout()
    {
        Auth::logout();
        return response()->json(array('status' => true, 'message' => "You logout Successfully", 'statuscode' => 200));
    }



    // public function getUsers()
    // {
    //     try {
    //         $users = User::with('role')->with('projects')->get();
    //         return $users;
    //     } catch (Exception) {
    //         return response()->json(array('status' => false, 'message' => "There is no Users", 'statuscode' => 400), 400);
    //     }
    // }
    // public function getUserById($id)
    // {
    //     try {
    //         $user = User::with('role')->with('projects')->findOrFail($id);
    //         return $user;
    //     } catch (Exception) {
    //         return response()->json(array('status' => false, 'message' => "No User Info Found for This id", 'statuscode' => 400), 400);
    //     }
    // }

    // public function userUpdate(UserRequest $request)
    // {


    //     try {
    //         // Check if user existed OR not 
    //         $user = User::findOrFail($request['user_id']);
    //         //    return $user['email'];
    //         $user['username'] = $request['username'];
    //         $user['password'] = $request['password'];
    //         $user['email'] = $request['email'];
    //         $user['role_id'] = $request['role_id'];
    //         $user['phone_number'] = $request['phone_number'];
    //         $user->update();

    //         return response()->json(array('status' => true, 'user' => $user, 'statuscode' => 200));
    //     } catch (Exception) {

    //         return response()->json(array('status' => false, 'message' => "No User Info Found for This id", 'statuscode' => 400), 400);
    //     }
    // }

    // public function userDelete($id)
    // {
    //     try {
    //         $user = User::where('id', $id)->first();
    //         $user->delete();
    //         return response()->json(array('status' => true, 'message' => "User Deleted", 'statuscode' => 204));
    //     } catch (Exception) {
    //         return response()->json(array('status' => false, 'message' => "No User Info Found for This id", 'statuscode' => 400), 400);
    //     }
    // }
}
