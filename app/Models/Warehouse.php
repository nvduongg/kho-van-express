<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    // Các trường có thể được gán giá trị hàng loạt (mass assignable)
    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'zip_code',
        'description',
        'is_active',
    ];

    // Định nghĩa mối quan hệ: Một kho hàng có nhiều mục tồn kho (inventory items)
    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }
}