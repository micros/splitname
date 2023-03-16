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
        Schema::create('split_sustitutions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('origin')->unique();
            $table->string('rule');
            $table->timestamps(6);
            $table->foreign('rule')->references('rule')->on('split_rules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('split_sustitutions');
    }
};
