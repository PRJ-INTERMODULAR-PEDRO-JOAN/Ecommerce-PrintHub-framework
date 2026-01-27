<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'text', 'rating'];

    // Relación con el Usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el Producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}