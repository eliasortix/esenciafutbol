<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            
            // 1. Relación con catálogo (opcional)
            // Usamos nullable() porque si la venta es manual, no habrá product_id
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');

            // 2. Nombre manual (por si no está en el catálogo)
            $table->string('product_name_manual')->nullable();

            // 3. Precios y Costes
            $table->decimal('cost_price', 8, 2); // Lo que te costó a ti
            $table->decimal('sale_price', 8, 2); // A cuánto lo has vendido

            // 4. Quién ha hecho la venta
            // Usamos string para guardar el nombre del socio o 'Web'
            $table->string('seller_name')->default('Web');

            // 5. Cálculos de beneficios (para reportes rápidos)
            $table->decimal('seller_commission', 8, 2)->default(0); // Parte del socio
            $table->decimal('company_profit', 8, 2); // Lo que se queda la empresa limpia

            $table->timestamps();
        });
    }
};
