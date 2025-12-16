<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Campos permitidos para asignación masiva (basado en tu migración)
    protected $fillable = [
        'sku',
        'name',
        'description',
        'image',
        'price',
        'stock',
        'category',
    ];

    // Relación: Un producto tiene muchos comentarios
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}