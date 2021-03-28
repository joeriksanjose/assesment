<?php

namespace App\Services;

use App\Mail\ConfirmRegistration;
use App\Models\User;
use App\Models\UserConfirmRegistration;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;

class UserService
{
    public function create($params)
    {
        \DB::beginTransaction();

        try {
            $user = User::create($params);

            // create 6 digits registration confirmation
            $randomSixDigitNumber = mt_rand(100000, 999999);
            UserConfirmRegistration::create([
                'user_id' => $user->id,
                'code' => $randomSixDigitNumber
            ]);

            \DB::commit();

            Mail::to($user->email)->send(new ConfirmRegistration($user, $randomSixDigitNumber));

            return $user;
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }

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

    public function confirmRegistration($id, $code)
    {
        \DB::beginTransaction();

        try {
            $userCode = UserConfirmRegistration::where('user_id', $id)
                ->where('code', $code);

            if (!$userCode->first()) {
                throw new Exception('User code not found');
            }

            $userCode->delete();

            // update registered_at
            $this->update($id, ['registered_at' => date('Y-m-d H:i:s')]);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
}
