<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use  App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
     /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get the authenticated User.
     *
     * @return Response
     */
    public function profile()
    {
        $resData = [
            'status'  => "success",
            'data'    =>  Auth::user(),
            'message' => 'successfully load data'
        ];
        return response()->json($resData, 200);
    }
    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required|string',
        ]);

        try {
            User::where('id',Auth::user()->id)->update([
                'name'     => $request->input('name'),
            ]);
            $resData = [
                'status'  => "success",
                'data'    =>  User::where('id', Auth::user()->id)->first()  ,
                'message' => 'Data updated successfully'
            ];
            return response()->json($resData, 201);
        } catch (\Exception $e) {
            return $e;
            $resData = [
                'status'    => "error",
                'data'      => null,
                'message'   => 'User Update Failed!'
            ];
            return response()->json($resData, 409);
        }
    }
    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'password'  => 'required|confirmed',
        ]);

        try {
            $credentials = [
                'email'     => Auth::user()->email,
                'password'  => $request->password,
            ];
            if (Auth::attempt($credentials)) {
                $resData = [
                    'status'    => "error",
                    'data'      => null,
                    'message'   => 'Password sama dengan sebelumnya!'
                ];
                return response()->json($resData, 401);
            }

            User::where('id', Auth::user()->id)->update([
                'password' => app('hash')->make($request->input('password')),
            ]);
            $resData = [
                'status'  => "success",
                'data'    =>  User::where('id', Auth::user()->id)->first(),
                'message' => 'Data updated successfully'
            ];
            return response()->json($resData, 201);
        } catch (\Exception $e) {
            return $e;
            $resData = [
                'status'    => "error",
                'data'      => null,
                'message'   => 'User Registration Failed!'
            ];
            return response()->json($resData, 409);
        }
    }

    /**
     * Get all User.
     *
     * @return Response
     */
    public function allUsers()
    {
        $resData = [
            'status'  => "success",
            'data'    =>  User::all(),
            'message' => 'successfully load data'
        ];
        return response()->json($resData, 200);
    }

    /**
     * Get one user.
     *
     * @return Response
     */
    public function singleUser($id)
    {
        try {

            $resData = [
                'status'  => "success",
                'data'    =>  User::findOrFail($id),
                'message' => 'successfully load data'
            ];
            return response()->json($resData, 200);

        } catch (\Exception $e) {
            $resData = [
                'status'    => "error",
                'data'      => null,
                'message'   => 'user not found!'
            ];
            return response()->json($resData, 404);

        }

    }

}
