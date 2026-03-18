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
    Schema::table('sales', function (Blueprint $table) {
        // Solo intentamos crear la columna si NO existe ya
        if (!Schema::hasColumn('sales', 'commission')) {
            $table->decimal('commission', 10, 2)->default(0.00);
        }
        
        if (!Schema::hasColumn('sales', 'supplier_product_name')) {
            $table->string('supplier_product_name')->nullable();
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['commission', 'supplier_product_name']);
        });
    }
};