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
        Schema::create('digital_twins_results', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('digital_twins_campaign_id')->constrained('digital_twins_campaigns')->onDelete('cascade');
            $table->foreignId('email_id')->nullable()->constrained('phishing_emails')->onDelete('set null');
            $table->boolean('state')->nullable();
            $table->dateTime('opened')->nullable();
            $table->dateTime('clicked')->nullable();
            $table->text('opened_explanation')->nullable();
            $table->text('clicked_explanation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_twins_results');
    }
};
