<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            // Relación con el catálogo de productos
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            
            // Detalles de la compra específica
            $table->string('supplier_product_name')->nullable(); 
            $table->decimal('cost_price', 8, 2); 
            $table->string('size')->nullable(); // XS, S, M, L, XL
            
            // Estado: true = disponible en el estante, false = vendido
            $table->boolean('is_sold')->default(false);
            
            $table->timestamps();
        });
    }
};
