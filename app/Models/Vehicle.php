<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_plate',
        'make',
        'model',
        'type',
        'capacity_weight',
        'capacity_volume',
        'status',
        'notes',
    ];

    // Định nghĩa mối quan hệ: Một phương tiện có thể có nhiều vận chuyển
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}