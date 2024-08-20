<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function getPermissionsAttribute()
    {
        return $this->attributes['permissions'] == null || $this->attributes['permissions'] == '' ? [] : json_decode($this->attributes['permissions']);
    }
}
