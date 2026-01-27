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
        'price',
        'stock',
        'category',
        'image',
    ];

    // Relación: Un producto tiene muchos comentarios
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}