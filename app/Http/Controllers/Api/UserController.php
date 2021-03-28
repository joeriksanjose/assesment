<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

    public function update(Request $request, $id)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'name' => 'sometimes|string',
            'user_name' => 'sometimes|string|min:4|max:20',
            'email' => 'sometimes|string|email|max:100|unique:users',
            'password' => 'sometimes|string|min:6',
            'user_role' => 'sometimes|integer'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $user = $this->userService->update($id, $params);

        return response()->json([
           'message' => 'Profile successfully updated',
           'user' => $user
        ]);
    }
}
