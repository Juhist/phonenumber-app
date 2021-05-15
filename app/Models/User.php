<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name',
        'email',
        'dateOfBirth',
        'isActive',
    ];

    public function phonenumbers()
    {
        return $this->hasMany('App\Models\PhoneNumber');
    }

}
