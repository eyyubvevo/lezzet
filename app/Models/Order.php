<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'phone',
        'address',
        'message',
        'total',
        'subtotal',
        'discount_total',
        'is_confirmation'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
