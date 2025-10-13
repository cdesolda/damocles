<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UsersSeeder::class);
        $this->call(PhishingTopicsSeeder::class);
        $this->call(PhishingPersuasionsSeeder::class);
        $this->call(PhishingEmotionalTriggersSeeder::class);
        $this->call(LLMsSeeder::class);
        $this->call(QuestionnairesSeeder::class);
        $this->call(ThreatsSeeder::class);
        $this->call(HumanFactorsSeeder::class);
        $this->call(TrainingPromptsSeeder::class);
        $this->call(UserHfThreatSeeder::class);
    }
}
