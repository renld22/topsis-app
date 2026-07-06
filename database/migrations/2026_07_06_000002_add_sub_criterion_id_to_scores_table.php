<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->foreignId('sub_criterion_id')
                ->nullable()
                ->after('criterion_id')
                ->constrained('sub_criteria')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->dropConstrainedForeignId('sub_criterion_id');
        });
    }
};
