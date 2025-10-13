<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('phishing_campaigns', function (Blueprint $table) {           
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->integer('number_emails')->nullable();
            $table->foreignId('topic_id')->nullable()->constrained('phishing_topics')->onDelete('cascade');
            $table->json('emotional_triggers')->nullable();
            $table->json('persuasions')->nullable();
            $table->foreignId('llm_id')->nullable()->constrained('llms')->onDelete('cascade');
            $table->text('prompt')->nullable();
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');
            $table->enum('state',['Draft','Ready','Live', 'Completed'])->default('Draft');
            $table->date('expiration_date');
            $table->integer('timing_email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('phishing_campaigns');
    }
};
