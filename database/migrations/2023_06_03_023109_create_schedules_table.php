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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regu_id')->constrained('regu')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('periode_id')->constrained('periode')->cascadeOnDelete()->cascadeOnUpdate();
            $table->tinyInteger('minggu');
            $table->tinyInteger('hari');
            $table->boolean('diterima')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
