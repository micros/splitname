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
        Schema::create('split_terms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('term');
            $table->string('type');
            $table->string('gender')->nullable();
            $table->string('canonical')->nullable();
            $table->timestamps(6);
            $table->unique(['term', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('split_terms');
    }
};
