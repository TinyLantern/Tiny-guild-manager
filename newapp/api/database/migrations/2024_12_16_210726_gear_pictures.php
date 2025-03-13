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
        Schema::create('gear_pictures', function (Blueprint $table) {
            $table->id();
            $table->string('file_path');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('character_id')->constrained('characters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gear_pictures');
    }
};
