<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'role'      => 'required',
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|confirmed',
        ]);

        try {
            $id = Str::random(36);
            User::create([
                'id'       => $id,
                'name'     => $request->input('name'),
                'role'     => $request->input('role'),
                'email'    => $request->input('email'),
                'password' => app('hash')->make($request->input('password')),
            ]);
            return response()->json(['data' => User::where('id',$id)->get(), 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }

    }


    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
          //validate incoming request
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }


}
