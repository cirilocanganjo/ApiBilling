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
         Schema::table('clients', function (Blueprint $table) {
        $table->foreignId('country_id')
            ->nullable()
            ->after('tax_id')
            ->constrained('countries')
            ->onDelete('cascade');

        $table->foreignId('province_id')
            ->nullable()
            ->after('country_id')
            ->constrained('provinces')
            ->onDelete('cascade');

        $table->foreignId('city_id')
            ->nullable()
            ->after('province_id')
            ->constrained('cities')
            ->onDelete('cascade');

        $table->string('complement',100)->nullable()->after('address');
        $table->string('neighborhood',100)->nullable()->after('complement');
        $table->string('postal_code',100)->nullable()->after('neighborhood');
        $table->string('recipient',150)->nullable()->after('postal_code');
        $table->text('notes')->nullable()->after('recipient');
        $table->string('email',100)->nullable()->after('phone'); 
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
