<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessVerifyUser extends Model
{
    protected $fillable = ['email', 'type', 'access'];

    public function newRegisterAccesMap()
    {
        return [
            'email' => $this->email, 'code' => $this->access
        ];
    }
}
