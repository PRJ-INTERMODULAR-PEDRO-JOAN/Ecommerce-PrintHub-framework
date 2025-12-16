<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('sku')->unique();
        $table->string('name'); // 'nom' al teu legacy
        $table->text('description')->nullable(); // 'descripcio'
        $table->string('image')->nullable(); // 'img'
        $table->decimal('price', 8, 2); // 'preu'
        $table->integer('stock'); // 'estoc'
        $table->string('category')->nullable(); // Per filtrar (ponts, vehicles, etc.)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
