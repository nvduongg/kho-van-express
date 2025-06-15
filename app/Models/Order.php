<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_date',
        'status',
        'total_amount', // Sẽ được tính toán tự động
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zip_code',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'datetime',
    ];

    /**
     * Get the customer that owns the order.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the order items for the order.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Một phương thức trợ giúp để lấy tổng số lượng sản phẩm trong đơn hàng
    public function getTotalQuantityAttribute()
    {
        return $this->orderItems->sum('quantity');
    }
}