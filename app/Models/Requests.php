<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Couriers;

class Requests extends Model
{
    /** @use HasFactory<\Database\Factories\RequstsFactory> */
    protected $table = 'requests';
    use HasFactory;
    protected $fillable = ['user_id', 'title', 'description', 'type','address', 'status', 'pickup_date','courier_id'];

    /**
     * العلاقة بين الطلب والمستخدم (كل طلب يخص مستخدم واحد).
     */
    public function users_dern()
    {
        return $this->belongsTo(Users::class);
    }
    public function couriers()
    {
        return $this->belongsTo(Couriers::class);
    }
}
