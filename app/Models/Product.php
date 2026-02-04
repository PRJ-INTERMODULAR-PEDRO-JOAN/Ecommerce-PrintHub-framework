<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'category',
        'sku',
        'rating',
        'reviews_count'
    ];

    // Relaciones existentes
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // --- NUEVA FUNCIÓN: OFERTA FLASH DEL DÍA ---
    public static function getDailyDeal()
    {
        // 1. Solo productos con stock
        $productIds = self::where('stock', '>', 0)->pluck('id')->toArray();

        if (empty($productIds)) {
            return null; 
        }

        // 2. Semilla basada en la fecha (AñoMesDia)
        // Esto garantiza que el random sea EL MISMO para todos durante 24h
        mt_srand(date('Ymd')); 
        
        // 3. Elegimos uno al azar
        $randomIndex = mt_rand(0, count($productIds) - 1);
        
        // 4. Devolvemos el producto
        return self::find($productIds[$randomIndex]);
    }
}