<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserConfirmRegistration extends Model
{
    protected $table = 'user_confirm_registration';
    protected $fillable = ['user_id', 'code'];
    public $timestamps = true;
}
