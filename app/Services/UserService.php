<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function create($params)
    {
        return User::create($params);
    }

    public function update($id, $params)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            throw new \Exception('User not found');
        }

        if (isset($params['user_name'])) {
            $user->user_name = $params['user_name'];
        }

        if (isset($params['password'])) {
            $user->password = bcrypt($params['password']);
        }

        if (isset($params['name'])) {
            $user->name = $params['name'];
        }

        if (isset($params['email'])) {
           $user->email = $params['email'];
        }

        if (isset($params['user_role'])) {
            $user->user_role = $params['user_role'];
        }

        if (isset($params['registered_at'])) {
            $user->registered_at = $params['registered_at'];
        }

        if (isset($params['avatar'])) {
            $user->avatar = $params['avatar'];
        }

        $user->save();

        return $user;
    }
}
