<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // ESTO ES LO QUE SEGURAMENTE TE FALTA
    protected $fillable = [
        'user_id',
        'total_price',
        'shipping_address',
        'status',
        'payment_method'
    ];

    // Relación: Un pedido tiene muchos items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}