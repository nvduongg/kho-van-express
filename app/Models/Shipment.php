<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'vehicle_id',
        'origin_warehouse_id',
        'destination_warehouse_id',
        'tracking_number',
        'shipment_date',
        'delivery_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'shipment_date' => 'date',
        'delivery_date' => 'date',
    ];

    /**
     * Get the order that the shipment belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the vehicle that is assigned to the shipment.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the origin warehouse for the shipment.
     */
    public function originWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'origin_warehouse_id');
    }

    /**
     * Get the destination warehouse for the shipment.
     */
    public function destinationWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'destination_warehouse_id');
    }

    // Quan hệ với Order Items thông qua Order
    public function shipmentItems()
    {
        return $this->hasManyThrough(OrderItem::class, Order::class, 'id', 'order_id', 'order_id', 'id');
    }
}