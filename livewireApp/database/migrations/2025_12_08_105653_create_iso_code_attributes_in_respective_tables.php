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
        $table->string('iso_code',10)->unique()->nullable()->after('name');
    });

    Schema::table('countries', function (Blueprint $table) {
        $table->string('iso_code',10)->unique()->nullable()->after('name');
    });

    Schema::table('provinces', function (Blueprint $table) {
        $table->string('iso_code',10)->unique()->nullable()->after('name');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
