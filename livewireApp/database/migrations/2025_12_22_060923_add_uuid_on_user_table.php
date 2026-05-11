<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
          $tables = [
            'users'
        ];

          foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $tableBlueprint) {
                    $tableBlueprint->uuid('uuid')->nullable()->after('id');
                });

                
               DB::table($table) // Preencher UUID para registros existentes
                ->whereNull('uuid')
                ->orderBy('id')
                ->chunk(100, function ($rows) use ($table) {
                    foreach ($rows as $row) {
                        DB::table($table)
                            ->where('id', $row->id)
                            ->update(['uuid' => Str::uuid()]);
                    }
                });

            }
        }

       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
