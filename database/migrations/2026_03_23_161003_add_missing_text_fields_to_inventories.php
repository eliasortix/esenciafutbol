<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inventories', function (Blueprint $table) {
            // Estos son los ÚNICOS campos que no tienes aún
            if (!Schema::hasColumn('inventories', 'dorsal_name')) {
                $table->string('dorsal_name')->nullable();
            }
            if (!Schema::hasColumn('inventories', 'dorsal_number')) {
                $table->string('dorsal_number')->nullable();
            }
            if (!Schema::hasColumn('inventories', 'patches_description')) {
                $table->string('patches_description')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn(['dorsal_name', 'dorsal_number', 'patches_description']);
        });
    }
};