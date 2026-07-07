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
        Schema::table('sub_criteria', function (Blueprint $table) {
            $table->integer('weight')->nullable()->after('value');
        });

        // Copy value to weight for existing subcriteria
        \DB::table('sub_criteria')->update(['weight' => \DB::raw('value')]);

        // Make weight column in criteria table nullable
        Schema::table('criteria', function (Blueprint $table) {
            $table->decimal('weight', 8, 4)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('criteria', function (Blueprint $table) {
            $table->decimal('weight', 8, 4)->nullable(false)->change();
        });

        Schema::table('sub_criteria', function (Blueprint $table) {
            $table->dropColumn('weight');
        });
    }
};
