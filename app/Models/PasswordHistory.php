<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class PasswordHistory extends Model
{
    protected $fillable = [
        'user_id',
        'password'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}