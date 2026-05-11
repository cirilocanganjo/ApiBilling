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
        Schema::table('cities', function (Blueprint $table) {
        $table->dropColumn('iso_code');  
        });

        Schema::table('provinces', function (Blueprint $table) {
        $table->dropColumn('iso_code');  
        });

        Schema::table('countries', function (Blueprint $table) {
        $table->dropColumn('iso_code');  
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
