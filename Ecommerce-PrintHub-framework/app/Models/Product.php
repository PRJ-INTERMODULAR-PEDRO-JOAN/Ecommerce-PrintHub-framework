<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku', 'name', 'description', 'price', 'stock', 'category', 'image',
    ];

    // Relación con Comentarios
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Relación con Likes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Función para saber si un usuario le dio like
    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likes->where('user_id', $user->id)->isNotEmpty();
    }

    // --- NUEVO: Calcular la media de estrellas ---
    public function getRatingAttribute()
    {
        // Redondeamos a 1 decimal (ej: 4.5)
        return round($this->comments()->avg('rating'), 1) ?? 0;
    }

    // --- NUEVO: Contar valoraciones ---
    public function getReviewsCountAttribute()
    {
        return $this->comments()->count();
    }
}   