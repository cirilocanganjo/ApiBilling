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
        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name', 150);
            $table->boolean('natural_person')->default(false);
            $table->string('tax_id', 30);            
            
            $table->foreignId('country_id')
            ->nullable()
            ->constrained('countries')
            ->onDelete('cascade');

            $table->foreignId('province_id')
            ->nullable()
            ->constrained('provinces')
            ->onDelete('cascade');

            $table->foreignId('city_id')
            ->nullable()
            ->constrained('cities')
            ->onDelete('cascade');
            
            $table->string('address', 255);
            $table->string('complement', 100)->nullable();
            $table->string('neighborhood', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('contact_person', 150)->nullable();
            $table->text('notes')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable();

            // Índices
            $table->index('tax_id', 'idx_suppliers_tax_id');
            $table->index('name', 'idx_suppliers_name');
        });
            
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies');
    }
};
