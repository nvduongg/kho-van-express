<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory'; // Đảm bảo đúng tên bảng nếu khác với tên số nhiều của Model

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity',
        'min_stock_level',
        'max_stock_level',
    ];

    // Định nghĩa mối quan hệ: Một mục tồn kho thuộc về một sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Định nghĩa mối quan hệ: Một mục tồn kho thuộc về một kho hàng
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}