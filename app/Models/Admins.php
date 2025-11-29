<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admins extends Model
{
    /** @use HasFactory<\Database\Factories\AdminsFactory> */
    protected $fillable = [
        'email',
        'password',
    ];
    protected $hidden = [
        'password',
    ];
    use HasFactory;
}
