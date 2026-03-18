<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('sales', function (Blueprint $table) {
        // Añadimos la columna que falta
        $table->unsignedBigInteger('inventory_id')->nullable()->after('id');
    });
}

public function down(): void
{
    Schema::table('sales', function (Blueprint $table) {
        $table->dropColumn('inventory_id');
    });
}
};
