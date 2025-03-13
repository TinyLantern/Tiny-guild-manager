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
        Schema::create('characters', function (Blueprint $table){
            $table->id();
            $table->string('character_name', length: 16)->nullable(false);
            $table->integer('dkp_points')->default(0);
            $table->integer('gear_score')->default(0)->nullable(true);
            $table->foreignId('primary_weapon_id')->constrained('weapons')->onDelete('cascade');
            $table->foreignId('secondary_weapon_id')->constrained('weapons')->onDelete('cascade');
            $table->foreignId('character_type_id')->constrained('character_types')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('guild_rank_id')->nullable()->constrained('guild_ranks')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
