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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name',150);
            $table->string('nif',30);
            $table->string('email',100)->unique();
            $table->string('phone', 50);
            $table->string('address')->nullable();
            $table->string('reference', 50)->unique();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->double('is_deleted')->default(0);
            $table->softDeletes();
            $table->bigInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
