<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Tabelas que precisam do campo UUID (exceto categories e users)
        $tables = [
            'brands',
            'clients',
            'companies',
            'cities',
            'units',
            'supplies',
            'subcategories',
            'provinces',
            'countries',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $tableBlueprint) {
                    $tableBlueprint->uuid('uuid')->nullable()->after('id');
                });

                // Preencher UUID para registros existentes
               DB::table($table)
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

    public function down(): void
    {
        $tables = [
            'brands',
            'clients',
            'companies',
            'cities',
            'units',
            'supplies',
            'subcategories',
            'provinces',
            'countries',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'uuid')) {
                Schema::table($table, function (Blueprint $tableBlueprint) {
                    $tableBlueprint->dropColumn('uuid');
                });
            }
        }
    }
};
