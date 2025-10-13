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
        Schema::create('digital_twins_campaigns', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('title');
            $table->text('description');
            $table->foreignId('threat_id')->nullable()->constrained('threats')->onDelete('set null');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');
            $table->text('demographics')->nullable();
            $table->json('human_factors')->nullable(); 
            $table->enum('user_prompt_type',['short','medium','detailed'])->default('short');            
            $table->text('user_prompt');
            $table->foreignId('llm_id')->constrained('llms')->onDelete('cascade');
            $table->enum('threat_prompt_type',['short','medium','detailed'])->default('short');            
            $table->text('threat_prompt');
            $table->json('emails_ids')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_twins_campaigns');
    }
};
