<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Requests;
class Couriers extends Model
{
    /** @use HasFactory<\Database\Factories\CouriersFactory> */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
    ];
    protected $hidden = [
        'password',
    ];
    public function requests()
    {
        return $this->hasMany(Requests::class);
    }
    use HasFactory;
}
