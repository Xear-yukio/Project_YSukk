<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'email',
        'province',
        'city',
        'district',
        'postal_code',
        'address',
        'notes',
        'total_amount',
        'payment_method',
        'payment_proof',
        'status',
        'courier_name',
        'tracking_number',
        'estimated_arrival',
        'current_location',
        'shipping_history'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
