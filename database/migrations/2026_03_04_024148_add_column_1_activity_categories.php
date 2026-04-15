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
        Schema::table('tp_3_activity_categories', function (Blueprint $table) {
            $table->integer('qty_total')->after('name')->nullable();
            $table->integer('qty_recived')->after('qty_total')->nullable();
            $table->integer('total_nominal')->after('qty_recived')->nullable();
            $table->integer('qty_nominal')->after('total_nominal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tp_3_activity_categories', function (Blueprint $table) {
            $table->dropColumn('qty_total');
            $table->dropColumn('qty_recived');
            $table->dropColumn('total_nominal');
            $table->dropColumn('qty_nominal');
        });
    }
};
