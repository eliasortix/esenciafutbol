<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            // Añadimos la cantidad de parches (0, 1 o 2)
            $table->integer('patches_qty')->default(0)->after('cost_price');
            
            // Añadimos si lleva nombre y dorsal (booleano: 0 o 1)
            $table->boolean('has_dorsal')->default(false)->after('patches_qty');
            
            // Añadimos el precio final calculado (coste base + extras)
            // Usamos decimal(8,2) para que soporte hasta 999.999,99€
            $table->decimal('total_computed_cost', 8, 2)->nullable()->after('has_dorsal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn(['patches_qty', 'has_dorsal', 'total_computed_cost']);
        });
    }
};