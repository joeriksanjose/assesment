<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use http\Env\Response;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'name' => 'required',
            'user_name' => 'required|string|min:4|max:20',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'user_role' => 'required|integer'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $params['password'] = bcrypt($params['password']);

        $user = $this->userService->create($params);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function update(Request $request)
    {

        $user = auth()->user();
        $params = $request->all();

        $validator = Validator::make($params, [
            'name' => 'sometimes|string',
            'user_name' => 'sometimes|string|min:4|max:20',
            'email' => 'sometimes|string|email|max:100|unique:users',
            'password' => 'sometimes|string|min:6',
            'user_role' => 'sometimes|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $user = $this->userService->update($user->id, $params);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }

        return response()->json([
           'message' => 'Profile successfully updated',
           'user' => $user
        ]);
    }

    public function confirm_registration(Request $request)
    {
        $code = $request->post('code');

        try {
            $this->userService->confirmRegistration(auth()->user()->id, $code);
            return response()->json(['message' => 'Account registration confirmed'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }

    }
}
