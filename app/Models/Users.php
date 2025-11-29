<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Users extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'users_dern';
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];
    protected $hidden = [
        'password',
    ];
    
}
