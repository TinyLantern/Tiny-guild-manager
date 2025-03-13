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
        Schema::create('guild_logos', function (Blueprint $table) {
            $table->id();
            $table->string('file_path');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('guild_id')->constrained('guilds')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guild_logos');
    }
};