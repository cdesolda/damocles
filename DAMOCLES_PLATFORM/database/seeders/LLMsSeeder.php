<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LLMsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('llms')->insert([
            [
                'endpoint' => 'https://api.openai.com/v1/chat/completions',
                'provider' => 'OpenAI',
                'model' => 'gpt-4o-mini',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
