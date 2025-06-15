<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'notes',
    ];

    // Định nghĩa mối quan hệ: Một khách hàng có nhiều đơn hàng
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}