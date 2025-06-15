<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'weight',
        'length',
        'width',
        'height',
        'unit',
        'price',
        'image_path',
        'is_active',
    ];

    // Định nghĩa mối quan hệ: Một sản phẩm có nhiều mục tồn kho
    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }

    // Định nghĩa mối quan hệ: Một sản phẩm có thể xuất hiện trong nhiều chi tiết đơn hàng
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    // Định nghĩa mối quan hệ: Một sản phẩm có thể xuất hiện trong nhiều mục tồn kho
    public function inventories()
    {
        return $this->hasMany(\App\Models\Inventory::class);
    }
}