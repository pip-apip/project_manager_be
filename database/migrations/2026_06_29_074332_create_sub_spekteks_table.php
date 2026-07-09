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
        Schema::create('tp_9_sub_spekteks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spektek_id')
                ->constrained('tp_8_spekteks')
                ->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', ['hardware', 'software']);
            $table->unsignedInteger('qty_total');
            $table->unsignedInteger('qty_received')->default(0);
            $table->unsignedInteger('qty_nominal')->nullable();
            $table->decimal('total_nominal', 18, 2)->nullable();
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->text('detail')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('qty_updated_at')->nullable();
            $table->timestamp('progress_updated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('spektek_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_9_sub_spekteks');
    }
};
