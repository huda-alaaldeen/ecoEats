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
        Schema::table('restaurants', function (Blueprint $table) {
            $table->time('working_hours_from')->after('role_id');
            $table->time('working_hours_to')->after('working_hours_from');
            $table->string('license')->nullable()->after('restaurant_info');
            $table->dropColumn('working_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['working_hours_from', 'working_hours_to', 'license']);
            $table->string('working_hours')->after('role_id');
        });
    }
};
