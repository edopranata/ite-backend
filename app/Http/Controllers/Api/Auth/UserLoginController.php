<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserLoginController extends Controller
{

    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        $login = $request->only(['username', 'password']);
        $device_id = $request->device_id ?? $request->username;
        $auth = auth()->attempt($login);

        if(!$auth){
            return $this->response(false, 'Invalid Credentials', 401, []);
        }

        $token = auth()->user()->createToken($device_id)->plainTextToken;

        $data = [
            'user'  => auth()->user(),
            'auth' => [
                'type'  => 'Bearer',
                'token' => $token
            ]
        ];
        return $this->response(true, '', 201, $data, []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
