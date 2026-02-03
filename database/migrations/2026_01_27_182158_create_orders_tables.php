<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla de Pedidos (Cabecera)
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('pendiente'); // pendiente, pagado, enviado
            $table->string('shipping_address');
            $table->string('payment_method')->default('tarjeta');
            $table->timestamps();
        });

        // Tabla de Líneas de Pedido (Detalle)
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->string('product_name'); // Guardamos nombre por si cambia el producto
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // Precio en el momento de la compra
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};