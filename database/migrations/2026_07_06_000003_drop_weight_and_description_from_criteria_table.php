<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('criteria', function (Blueprint $table) {
            if (Schema::hasColumn('criteria', 'weight')) {
                $table->dropColumn('weight');
            }
            if (Schema::hasColumn('criteria', 'description')) {
                $table->dropColumn('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('criteria', function (Blueprint $table) {
            $table->float('weight')->default(0);
            $table->text('description')->nullable();
        });
    }
};
