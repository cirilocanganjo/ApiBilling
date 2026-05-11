<?php

use App\Models\Country;
use App\Models\Province;
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
       Schema::table('countries', function (Blueprint $table) {
            $table->char('iso_code',2)->unique()->after('id');
            $table->string('name', 150)->after('iso_code');
            $table->bigInteger('created_by')->after('updated_at')->nullable();
            $table->bigInteger('updated_by')->after('created_by')->nullable();
            $table->double('is_deleted')->after('updated_by')->default(0);
            $table->softDeletes()->after('is_deleted');
            $table->bigInteger('deleted_by')->after('deleted_at')->nullable();
        });

       Schema::table('provinces', function (Blueprint $table) {
        $table->foreignIdFor(Country::class)->after('id');
        $table->string('name', 150)->after('country_id');
        $table->char('iso_code',2)->unique()->after('name');
        $table->bigInteger('created_by')->after('updated_at')->nullable();
        $table->bigInteger('updated_by')->after('created_by')->nullable();
        $table->double('is_deleted')->after('updated_by')->default(0);
        $table->softDeletes()->after('is_deleted');
        $table->bigInteger('deleted_by')->after('deleted_at')->nullable();
        });

       Schema::table('cities', function (Blueprint $table) {
            $table->foreignIdFor(Province::class)->after('id');
            $table->string('name', 150)->after('province_id');
            $table->char('iso_code',2)->unique()->after('name');
            $table->bigInteger('created_by')->after('updated_at')->nullable();
            $table->bigInteger('updated_by')->after('created_by')->nullable();
            $table->double('is_deleted')->after('updated_by')->default(0);
            $table->softDeletes()->after('is_deleted');
            $table->bigInteger('deleted_by')->after('deleted_at')->nullable();
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
