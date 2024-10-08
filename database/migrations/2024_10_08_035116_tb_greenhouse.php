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
        Schema::create('greenhouse', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->dateTime('spk_creation_date');
            $table->dateTime('deadline_for_slaughterhouse_entry');
            $table->boolean("is_done")->default(false);
            $table->dateTime('entry_time_to_slaughterhouse')->nullable();
            $table->time('required_duration')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("greenhouse");
    }
};
